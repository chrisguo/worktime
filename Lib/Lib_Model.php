<?php
class Lib_Model {
    protected $_ckey = '';
    protected $_ctime = 0;

    protected $_table = '';//表名
    protected $_pk = '';//主键
    protected $_db_key = '';//数据库索引

    protected $_db_saved = '';

    protected $_u = array();//更新字段

    protected static $_instances = array();
    static public function readone($id) {
        if (!isset(self::$_instances[$id])) {
            $class_name = get_called_class();
            $t = new $class_name();
            $t->init_from_id($id);
            self::$_instances[$id] = $t;
        }

        return self::$_instances[$id];
    }

    public function set_db_key($key) {
        $this->_db_key = $key;
    }

    public function set_table($table) {
        $this->_table = $table;
    }

    public function get_table() {
        return $this->_table;
    }

    public function get_table_cehua($table) {
        return Lib_Functions::get_cehua_table($table);
    }

    public function get_ckey() {
        return $this->_ckey;
    }

    public function set_ckey($key) {
        $this->_ckey = $key;
    }

    public function init_ckey($id) {
        $class_name = get_called_class();
        $this->_ckey = $class_name . '_' . $id;
    }

    public function get_memcache() {
        return new Lib_Memcache();
    }

    public function get_db() {
        return Pro_Db::get_mysql();
    }

    public function init_first2($key, $pk_value) {
        $sql = $this->get_sql('where ' . $this->_pk . '="' . addslashes($pk_value) . '"');
        return $this->init_first($key, $sql);
    }

    public function init_first($key, $sql) {
        $this->set_ckey($key);
        if (!$this->init_from_memcache()) {
            if (!$this->init_from_db($sql)) {
                return false;
            } else {
                $this->init_before_save_memcache();
                $this->save_to_memcache();
            }
        }
        return true;
    }

    public function init_after() {
        return ;
    }

    public function init_before_save_memcache() {
        return ;
    }

    public function init_from_memcache() {
        if (!$this->_ckey) {
            return false;
        }
        $memcache = $this->get_memcache();

        $t = $memcache->get($this->_ckey);
        if (!is_object($t)) {
            return false;
        } else {
            $vars = $t->get_vars();
            foreach ($vars as $key => $val) {
                $this->$key = $val;
            }
            return true;
        }
    }

    public function get_vars() {
        return get_object_vars($this);
    }

    public function delete_from_memcache() {
        $memcache = $this->get_memcache();
        return $memcache->delete($this->_ckey);
    }

    public function is_in_db() {
        return $this->_db_saved;
    }

    public function init_from_id($id) {
        $sql = $this->get_sql('where ' . $this->_pk . '="' . addslashes($id) . '"');
        return $this->init_from_db($sql);
    }

    public function init_from_db($sql) {
        if ('' == $this->get_table()) {
            return false;
        }
        $db = $this->get_db();
        $row = $db->fetch_first($sql);
        if ($row) {
            $this->_db_saved = true;
            $this->array2obj($row);
            $this->after_init_from_db();
		} else {
            $this->_db_saved = false;
        }

        return $this->_db_saved;
    }

    public function array2obj($a) {
        $serialized = $this->get_s();
        foreach ($a as $k =>$v) {
            if (in_array($k, $serialized)) {
                if ('' != $v) {
                    $this->$k = unserialize(gzuncompress($v));
                }
            } else {
                $this->$k = $v;
            }
        }
    }

    protected function get_s() {
        return array();
    }

    public function flush() {
        if (!$this->_u) {
            return ;
        }

        if ($this->get_table()) {
            if ($this->_db_saved) {
                $this->update();
            } else {
                $this->insert();
            }
        }
        $this->_u = array();
        $this->save_to_memcache();
    }

    public function save_to_memcache() {
        if ($this->_ckey) {
            $memcache = $this->get_memcache();
            $memcache->set($this->_ckey, $this, 0, $this->_ctime);
        }
    }

    public function set($col, $do = '', $val = '') {
		$this->_u[$col] = '';
        if ('=' == $do) {
            $this->$col = $val;
        } elseif ('+' == $do) {
            $this->$col += $val;
        } elseif ('-' == $do) {
            $this->$col -= $val;
        }
        $this->_u[$col] = $this->$col;
        return $this->$col;
	}

    public function insert() {
        $db = $this->get_db();
		$insert_id = $db->insert($this->get_table(), get_object_vars($this), $this->_pk);

        if ($this->_pk && !$this->{$this->_pk}) {
			$this->{$this->_pk} = $insert_id;
		}

        $this->_u = array();
        $this->_db_saved = 1;

		return $insert_id;
    }

    public function update() {
		if (!$this->_u || !$this->_pk) {
			return 0;
		}

        $db = $this->get_db();

        $condition = array(
            $this->_pk => $this->{$this->_pk}
        );

        $affected_rows = $db->update($this->get_table(), $this->_u, $condition);

        $this->_u = array();

        $this->update_after();
        return $affected_rows;
	}

    public function delete() {
        if ($this->_ckey) {
            $memcache = $this->get_memcache();
            $memcache->delete($this->_ckey);
        }

        if ($this->_pk) {
            $db = $this->get_db();
            $sql = 'delete from ' . $this->get_table() . ' where ' . $this->_pk . '="' . addslashes($this->{$this->_pk}) . '"';
            $db->query($sql);
        }

        $this->_db_saved = 0;
        $this->delete_after();
	}

    public function get_sql($sqladd = '') {
        return 'select * from ' . $this->get_table() . ' ' . $sqladd;
    }

    public function fetch_all($sqladd = '', $id = '') {
        $sql = $this->get_sql($sqladd);
        return $this->fetch_query($sql, $id);
    }

    public function fetch_query($sql, $id = '') {
        $rtn = array();

        $db = $this->get_db();

        $query = $db->query($sql);
        while ($row = $db->fetch_array($query)) {
            $t = Lib_Functions::clone_o($this);
            $t->array2obj($row);
            $id ? $rtn[$row[$id]] = $t : $rtn[] = $t;
        }
        return $rtn;
    }

	public function get_id() {
		return $this->{$this->_pk};
	}

    public function set_id($id) {
        $this->{$this->_pk} = $id;
    }

    public function get_pk_name() {
        return $this->_pk;
    }

    protected function insert_before() {
		return true;
	}

	protected function insert_after() {
		return true;
	}

	protected function update_before() {
		return true;
	}

	protected function update_after() {
		return true;
	}

	protected function delete_before() {
		return true;
	}

	protected function delete_after() {
		return true;
	}

	protected function after_init_from_db() {
		return true;
	}
}
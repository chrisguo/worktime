<?php
class Lib_Mysql {
	public $querynum = 0;
	public $link = null;
	public $histories;

	public $host;
	public $username;
	public $password;
	public $char = 'utf8';
	public $pnet;
    public $dbname;

	public $goneaway = 5;

	function __construct($array) {
		foreach ($array as $var => $val) {
            $this->$var = $val;
        }
    }

    private function _connect() {
        if ($this->link) {
            return true;
        }

        if($this->pnet) {
			if(!$this->link = mysql_pconnect($this->host, $this->username, $this->password)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = mysql_connect($this->host, $this->username, $this->password)) {
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->char) {
            mysql_query("SET character_set_connection=".$this->char.", character_set_results=".$this->char.", character_set_client=binary", $this->link);
        }

        mysql_query("SET sql_mode=''", $this->link);

        mysql_select_db($this->dbname, $this->link);
    }

	public function fetch_first($sql) {
		$query = $this->query($sql);
		return $this->fetch_array($query);
	}

	public function fetch_all($sql, $id = '') {
		$arr = array();
		$query = $this->query($sql);
		while($data = $this->fetch_array($query)) {
			$id ? $arr[$data[$id]] = $data : $arr[] = $data;
		}
		return $arr;
	}

    private function _ignore($k) {
        if ('_nodb' == substr($k, -5) || '_' == substr($k, 0, 1)) {
            return true;
        } else {
            return false;
        }
    }

	public function insert($table, $data, $return_insert_id = false, $replace = false) {
		$sql = $this->implode_field_value($data);
        if ('' == $sql) {
            return 0;
        }

		$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';

		$sql = "$cmd $table SET $sql";
		$return = $this->query($sql);
		return $return_insert_id ? $this->insert_id() : $return;
	}

	public function update($table, $data, $condition) {
		$sql = $this->implode_field_value($data);

		if ('' == $sql) {
			return 0;
		}

		if (empty($condition)) {
			$where = '1';
		} elseif (is_array($condition)) {
			$where = $this->implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}

		$sql = 'UPDATE ' . $table . ' SET ' . $sql . ' WHERE ' . $where;
		$this->query($sql);
		return $this->affected_rows();
	}

    function delete($table, $condition, $limit = 0) {
		if (empty($condition)) {
			$where = '1';
		} elseif (is_array($condition)) {
			$where = $this->implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}

		$sql = 'DELETE FROM ' . $table . ' WHERE ' . $where  . ($limit ? ' LIMIT $limit ' : '');
		return $this->query($sql);
	}

	public function implode_field_value($array, $glue = ',') {
		$sql = $comma = '';
		foreach ($array as $k => $v) {
			if (!$this->_ignore($k)) {
                if (is_array($v) || is_object($v)) {
                    $v = gzcompress(serialize($v));
                }
				$sql .= $comma . '`' . $k . '`=\'' . addslashes($v) . '\'';
				$comma = $glue;
			}
		}
		return $sql;
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		$one = mysql_fetch_array($query, $result_type);
		if (!$one) {
			$one = array();
		}
		return $one;
	}

	function result_first($sql) {
		$query = $this->query($sql);
		return $this->result($query, 0);
	}

	function query($sql, $type = '', $cachetime = FALSE) {
        //Lib_Functions::write_log('abc', $sql);
        $this->_connect();

		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link)) && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		$this->histories[] = $sql;
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') {
		$error = mysql_error();
		$errorno = mysql_errno();
		if($errorno == 2006 && $this->goneaway-- > 0) {
			$this->_connect($this->host, $this->user, $this->pawd, $this->char, $this->pnet);
			$this->query($sql);
		} else {
			$s = '<b>Error:</b>'.$error.'<br />';
			$s .= '<b>Errno:</b>'.$errorno.'<br />';
			$s .= '<b>SQL:</b>:'.$sql;
			exit($s);
		}
	}

    public function exec($sql) {
        return $this->query($sql);
    }

    public function beginTransaction() {

    }

    public function commit() {

    }

    public function rollBack() {

    }
}
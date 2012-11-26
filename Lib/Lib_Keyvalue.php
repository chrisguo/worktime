<?php
class Lib_Keyvalue extends Lib_Model {
    protected $_table = '';
    protected $_pk = 'id';

    public $id = '';
    public $body = '';

    protected function get_s() {
        return array('body');
    }

    static public function readone($instance) {
        $db_key = $instance->get_db_key();
        $table_name = $instance->get_table();
        $key = $instance->get_memcache_key();

        $Lib_Memory = Lib_Memory::get_instance();
        if (!$Lib_Memory->exist($key)) {
            $t = new self();
            $t->set_db_key($db_key);
            $t->set_table($table_name);
            $t->id = $instance->get_id();
            $t->init_first2($key, $instance->get_id());
            $Lib_Memory->add($key, $t, false);
        }
        return $Lib_Memory->find($key);
    }

    static public function readall($instance) {
        $table_name = $instance->get_table();
        $key = 'TMJ_Keyvalue_' . $table_name;

        $Lib_Memory = Lib_Memory::get_instance();
        if (!$Lib_Memory->exist($key)) {
            $db_key = $instance->get_db_key();
            $t = new self();
            $t->set_db_key($db_key);
            $t->set_table($table_name);

            $memcache = $t->get_memcache();
            $data = $memcache->get($key);
            if (!is_array($data)) {
                $all = $t->fetch_all('', $t->get_pk_name());
                $data = array();
                foreach ($all as $k => $v) {
                    $data[$k] = $v->body;
                }
                $memcache->set($key, $data, 0, 0);
            }
            $Lib_Memory->add($key, $data, false);
        }
        return $Lib_Memory->find($key);
    }
}
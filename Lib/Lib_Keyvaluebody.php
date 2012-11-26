<?php
class Lib_Keyvaluebody {

    public static function readone($id) {
        $class_name = get_called_class();
        $key = Lib_Functions::concat_(array($class_name, $id));
        $Lib_Memory = Lib_Memory::get_instance();
        if (!$Lib_Memory->exist($key)) {
            $class = new $class_name($id);
            $t = Lib_Keyvalue::readone($class);
            if (!$t->body) {
                $t->body = $class;
            }
            $t->body->init_after();
            $Lib_Memory->add($key, $t->body, false);
        }
        return $Lib_Memory->find($key);
    }

    public $id = '';
    
    public $db = 0;

    public function __construct($id) {
        $this->id = $id;
    }

    public function init_after() {}

    public function get_id() {
        return $this->id;
    }

    public function get_db_key() {return '';}
    public function get_table() {}

    public function get_memcache() {}

    public function is_in_db() {
        return $this->db;
    }

    public function on_changed() {
        $t = Lib_Keyvalue::readone($this);
        $t->set('body');

        $this->db = 1;
        $Lib_Memory = Lib_Memory::get_instance();
        $Lib_Memory->add($t->get_ckey(), $t, true);
    }

    public function cehua2db() {
        $this->delete();

        $t = new Lib_Keyvalue();
        $t->id = $this->get_id();
        $t->set_db_key($this->get_db_key());
        $t->set_table($this->get_table());

        $t->set('body', '=', $this);
        $t->flush();
    }

    public function truncate() {
        $t = new Lib_DbModel();
        $t->set_db_key($this->get_db_key());
        $t->set_table($this->get_table());

        $sql = 'TRUNCATE TABLE ' . $t->get_table();
        $db = Pro_Db::get_mysql();
        $db->query($sql);
    }

    public function delete() {
        $t = new Lib_Keyvalue();
        $t->set_db_key($this->get_db_key());
        $t->set_table($this->get_table());

        $sql = 'delete from ' . $t->get_table() . ' where id="' . $this->id . '"';
        $db = Pro_Db::get_mysql();
        $db->query($sql);

        $memcache = $t->get_memcache();
        $memcache->delete($this->get_memcache_key());
    }

    public function get_memcache_key() {
        return 'TMJ_Keyvalue_' . $this->get_db_key() . '_' . $this->get_table() . '_' . $this->get_id();
    }
}
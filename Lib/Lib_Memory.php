<?php
class Lib_Memory {
    static private $_instance = null;
    static public function get_instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private $_all = array();
    private $_flush = array();
    public function add($key, $val, $is_flush) {
        if (!isset($this->_all[$key])) {
            $this->_all[$key] = $val;
        }
        
        if ($is_flush) {
            $this->_flush[$key] = $key;
        }
        
        return $this->_all[$key];
    }

    public function exist($key) {
        return isset($this->_all[$key]);
    }

    public function find($key) {
        return $this->_all[$key];
    }

    public function end() {
        foreach ($this->_flush as $key) {
            $o = $this->find($key);
            if (method_exists($o, 'flush')) {
                $o->flush();
            }
        }
        $this->_all = array();
    }

    static public function on_end() {
        $t = self::get_instance();
        $t->end();
    }
}
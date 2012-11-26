<?php
class Lib_Memcache extends Memcache {
    private $prekey = '';
    private $default_expire = 0;

    private $is_open = false;

    public function __construct() {
        
    }

    function init($options, $prekey, $default_expire) {
        $this->prekey = $prekey . '_';

        $this->default_expire = $default_expire;

        foreach ($options as $option) {
            if ($option['is_open']) {
                $this->addServer($option['host'], $option['port'], $option['persistent']);
                $this->is_open = true;
            }
        }
        $this->setCompressThreshold(2000, 0.2);
    }

    public function format_key($key) {
        return md5($this->prekey . '_' . $key);
    }

    public function set($key, $var, $flag = 1, $expire = 0) {
        if (!$this->is_open) {
            return false;
        }
        
        $key = $this->format_key($key);
        if (!$expire) {
            $expire = $this->default_expire;
        }
        return parent::set($key, $var, $flag, $expire);
    }

    public function get($key) {
        if (!$this->is_open) {
            return false;
        }
        
        if (is_array($key)) {
            foreach ($key as &$ttt) {
                $ttt = $this->format_key($ttt);
            }
        } else {
            $key = $this->format_key($key);
        }

        return parent::get($key);
    }

    public function delete($key) {
        if (!$this->is_open) {
            return false;
        }
        
        $key = $this->format_key($key);
        return parent::delete($key);
    }

    public function add($key, $var, $flag = 1, $expire = 0) {
        if (!$this->is_open) {
            return false;
        }
        
        $key = $this->format_key($key);
        return parent::add($key, $var, $flag, $expire);
    }

    public function increment($key, $value = 1) {
        if (!$this->is_open) {
            return false;
        }
        
        $key = $this->format_key($key);
        return parent::increment($key, $value);
    }

    public function decrement($key, $value = 1) {
        if (!$this->is_open) {
            return false;
        }
        
        $key = $this->format_key($key);
        return parent::decrement($key, $value);
    }

    public function replace($key, $var, $flag = 1, $expire = 0) {
        if (!$this->is_open) {
            return false;
        }
        
        $key = $this->format_key($key);
        return parent::replace($key, $var, $flag, $expire);
    }
}
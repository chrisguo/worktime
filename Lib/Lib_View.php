<?php
class Lib_View {

    protected $_vars = array();//变量

    protected $_dir = '';//模板目录

    public function set_dir($dir) {
        $this->_dir = $dir;
    }
    
    public function display($tpl) {
        include $this->_dir . $tpl . '.php';
        exit();
    }

    public function fetch($tpl) {
        ob_start();
        include $this->_dir . $tpl . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        return str_replace(array("\r\n", "\n"), '', $content);
    }
    
    protected function _include($tpl) {
        include $this->_dir . $tpl . '.php';
    }
}
<?php

class O_Sys extends Lib_Model {
    protected $_table = 'sys';
    protected $_pk = 'id';

    public $id = 0;
    public $list = array();

    public function get_s() {
        return array('list');
    }

    const SYS_ROLES = 10001;
    const SYS_DEPARTMENT = 10002;

    public function init_from_id($id) {
        $this->id = $id;
        parent::init_from_id($id);
    }
}
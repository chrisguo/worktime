<?php
class O_Ticket extends Lib_Model {
    protected $_table = 'ticket';
    protected $_pk = 'id';

    public $id = 0;
    public $acter_id = 'admin';//负责人
    public $reporter_id = 0;//报告人

    public $caty = 0;//分类
    public $priority = 0;//优先级

    public $department = 'admin';

    public $title = '';
    public $content = '';

    public $log = array();

    public $t = 0;
    public $last_t = 0;
    public $status = Config_App::STATUS_NEW;
    public $test_status = Config_App::TEST_STATUS_NULL;

    public $test_report = array();

    //ALTER TABLE `ticket` ADD `version` INT NOT NULL
    public $version = 0;

    public function get_s() {
        return array('log', 'test_report');
    }

    function insert() {
        $this->t = RUNTIME;
        $this->last_t = RUNTIME;
        parent::insert();
    }

    function update() {
        $this->last_t = RUNTIME;
        $this->set('last_t');
        parent::update();
    }
}
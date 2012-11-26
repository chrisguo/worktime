<?php
//ALTER TABLE `ticket` ADD `test_report` BLOB NOT NULL
class O_TicketTestReport {
    public $t = 0;

    public $acter_name = Config_Admin::DEFAULT_SYS;
    public $status = 0;
    public $content = '';

    function __construct() {
        $this->t = RUNTIME;
    }
}
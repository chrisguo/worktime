<?php

class O_TicketLog {
    public $t = 0;
    public $name = '';
    public $k = '';
    public $v = '';
    
    function __construct() {
        $this->t = RUNTIME;
    }
}
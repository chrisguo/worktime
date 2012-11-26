<?php

class Ctl_Ticket extends Ctl_Base {
    public function index() {
        $id = Lib_Req::post('id');

        $O_Ticket = new O_Ticket();
        $O_Ticket->init_from_id($id);
        if (!$O_Ticket->is_in_db()) {
            $this->on_msg('ID 不正确');
        }

        $this->view->ticket = $O_Ticket;

        $this->ajax->popup('ticket');
        $this->on_ajax();
    }
}
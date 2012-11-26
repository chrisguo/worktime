<?php

class Ctl_Manage extends Ctl_Base {
    function __construct() {
        parent::__construct();

        if (!$this->acter->is_super_admin()) {
            $this->on_msg('没有权限');
        }
    }

    public function show_edit_role() {
        $id = Lib_Req::post('id');
        $O_Role = new O_Role();
        if ($id) {
            $O_Sys = O_Sys::readone(O_Sys::SYS_ROLES);
            if (isset($O_Sys->list[$id])) {
                $O_Role = $O_Sys->list[$id];
            }
        }
        $this->view->O_Role = $O_Role;

        $this->ajax->popup('role/form');
        $this->on_ajax();
    }

    public function get_role_list() {
        $this->ajax->add_innerhtml('#detail_box', 'role/index');
        $this->on_ajax();
    }

    public function set_role() {
        $row = Lib_Req::post('row');

        $O_Role = new O_Role();
        foreach ($row as $k => $v) {
            $t = trim($v);
            if ('' == $t) {
                $this->on_msg($k . ' is empty');
            }
            $O_Role->$k = $t;
        }

        $O_Sys = O_Sys::readone(O_Sys::SYS_ROLES);

        $O_Sys->list[$O_Role->id] = $O_Role;
        $O_Sys->set('list');
        $O_Sys->flush();

        $this->_after_change_role();
    }

    private function _after_change_role() {
        $this->ajax->add_eval_js('ajax("ctl=Manage&act=get_role_list");');
        $this->ajax->add_eval_js('cls("add_role");');

        $this->on_ajax();
    }

    public function delete_role() {
        $id = trim(Lib_Req::post('id'));
        if ('' == $id || 'admin' == $id) {
            $this->on_ajax();
        }

        $O_Sys = O_Sys::readone(O_Sys::SYS_ROLES);

        if (!isset($O_Sys->list[$id])) {
            $this->on_ajax();
        }
        unset($O_Sys->list[$id]);
        $O_Sys->set('list');
        $O_Sys->flush();

        $O_Ticket = new O_Ticket();
        $db = $O_Ticket->get_db();
        $sql = 'update ' . $O_Ticket->get_table() . ' set acter_id="admin" where acter_id="' . $id . '"';
        $db->query($sql);

        $sql = 'update ' . $O_Ticket->get_table() . ' set reporter_id="admin" where reporter_id="' . $id . '"';
        $db->query($sql);

        $this->_after_change_role();
    }

    public function get_department_list() {
        $this->ajax->popup('role/department');
        $this->on_ajax();
    }

    public function set_department() {
        $name = trim(Lib_Req::post('name'));
        if ('' == $name) {
            $this->on_ajax();
        }

        $O_Sys = O_Sys::readone(O_Sys::SYS_DEPARTMENT);
        if (Lib_Req::post('delete')) {
            if ('admin' == $name) {
                $this->on_ajax();
            }
            if (!isset($O_Sys->list[$name])) {
                $this->on_ajax();
            }

            unset($O_Sys->list[$name]);

            $O_Ticket = new O_Ticket();
            $db = $O_Ticket->get_db();
            $sql = 'update ' . $O_Ticket->get_table() . ' set department="admin" where department="' . $name . '"';
            $db->query($sql);
        } else {
            $O_Sys->list[$name] = $name;
        }

        $O_Sys->set('list');
        $O_Sys->flush();

        $this->get_department_list();
    }
}
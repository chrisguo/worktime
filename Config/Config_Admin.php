<?php

class Config_Admin {

    const DEFAULT_SYS = 'admin';

    public function get_acter($id) {
        $a = $this->get_acter_list();
        return isset($a[$id]) ? $a[$id] : null;
    }

    public function get_acter_list() {
        $O_Sys = O_Sys::readone(O_Sys::SYS_ROLES);
        return $O_Sys->list;
    }

    static public function get_select_option_list() {
        $rtn = array();
        $self = new self();
        $admins = $self->get_acter_list();
        foreach ($admins as $admin) {
            $admin instanceof O_Role;
            $rtn[$admin->id] = $admin->id;
        }
        return $rtn;
    }
}

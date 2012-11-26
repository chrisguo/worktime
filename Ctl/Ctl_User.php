<?php

class Ctl_User extends Ctl_Base {
    public function set_password() {
        $id = $this->acter_id;

        $O_Role = new O_Role();
        if ($id) {
            $O_Sys = O_Sys::readone(O_Sys::SYS_ROLES);
            if (isset($O_Sys->list[$id])) {
                $O_Role = $O_Sys->list[$id];
            } else {
                $this->on_ajax();
            }

            $password = Lib_Req::post('password');
            if ($password) {
                $O_Role->passwd = $password;
                $O_Sys->list[$O_Role->id] = $O_Role;
                $O_Sys->set('list');
                $O_Sys->flush();

                $this->ajax->add_eval_js('cls("add_role");');
                $this->on_msg('成功');
            }
        }

        $this->view->O_Role = $O_Role;

        $this->ajax->popup('role/password');
        $this->on_ajax();
    }
}

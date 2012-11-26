<?php
class Ctl_Role extends Ctl_Base {

    public function logout() {
        //$this->ajax->popup('server/online');
        $this->ajax->popup('login-form');
        $this->on_ajax();
    }

    public function reg_form() {
        //$this->ajax->popup('server/online');
        $this->ajax->popup('login-form');
        $this->on_ajax();
    }

    public function login() {
        $name = Lib_Req::post('login_name');
        $passwd = Lib_Req::post('passwd');

        $name = trim($name);

        $error = false;
        if ($name) {
            $Config_Admin = new Config_Admin();
            $role = $Config_Admin->get_acter($name);

            if ($role) {
                if ($passwd == $role->passwd) {
                    $this->_login_session($role);
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }

        if ($error) {
            $this->on_msg('登陆名或者密码错误');
        }

        if (!Lib_Req::post('login_state')) {
            $this->ajax->add_eval_js('LOGIN_STATE = 1;');
            $this->_after_login();
        }

        $this->ajax->innerhtml('#login_acter', $role->id);
        $this->ajax->add_eval_js('cls("login-form");');
        $this->ajax->add_eval_js('keep_session();');

        $this->on_ajax();
    }

    public function keep_session() {
        $this->on_ajax();
    }

    public function install() {
        
        $this->init_db_structure();
        
        $SYS_ROLES = O_Sys::readone(O_Sys::SYS_ROLES);

        $O_Role = new O_Role();
        $O_Role->id = 'admin';
        $O_Role->passwd = '123456';
        $O_Role->manager = 1;

        if (isset($SYS_ROLES->list[$O_Role->id])) {
            $this->_install_ok();
        }
        
        $SYS_ROLES->list[$O_Role->id] = $O_Role;
        $SYS_ROLES->set('list');
        $SYS_ROLES->flush();

        $SYS_DEPARTMENT = O_Sys::readone(O_Sys::SYS_DEPARTMENT);
        $SYS_DEPARTMENT->list['admin'] = 'admin';
        $SYS_DEPARTMENT->set('list');
        $SYS_DEPARTMENT->flush();

        $this->_install_ok();
    }
    
    private function _install_ok() {
        $this->ajax->popup('install_ok');
        $this->on_ajax();
    }
    
    public function init_db_structure() {
        $db = Pro_Db::get_mysql();
        
        $db_name = Config_Common::$db['dbname'];
        $sql_file = 'install.sql';
        
        $sql = file_get_contents($sql_file);

        $sql = trim($sql);

        $sql = str_replace("\r", "\n", str_replace('common_db_name', $db_name, $sql));

        $a = array();
        $i = 0;

        $tables = explode(";\n", $sql);
        foreach($tables as $table) {
            $a[$i] = '';
            $queries = explode("\n", trim($table));
            foreach($queries as $query) {
                $a[$i] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && $query[0] . $query[1] == '--') ? '' : $query;
            }
            $i++;
        }

        foreach($a as $query) {
            $query = trim($query);
            if ($query) {
                $db->query($query);
            }
        }
    }
}
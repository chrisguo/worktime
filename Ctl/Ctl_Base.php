<?php
class Ctl_Base {
    protected $act = 0;

    protected $acter_id = '';
    protected $acter = null;

    protected $ajax = null;
    protected $view = null;

    function  __construct() {
        $this->acter = new O_Role();

        $this->view = new Lib_View();
        $this->view->set_dir(ROOT_DIR . 'View/');


        $this->ajax = new O_Response();
        $this->ajax->set_view($this->view);

        if ('Role' != CTL) {
            $this->_init_acter();
        }
    }

    public function get_role() {
        return $this->acter;
    }

    public function get_role_id() {
        return $this->acter_id;
    }

    protected function __flush() {
        Lib_Memory::on_end();
    }

    public function display($tpl) {
        $this->__flush();

        $this->view->display($tpl);
        exit();
    }

    public function on_ajax() {
        $this->__flush();
        echo $this->ajax->get_content();exit();
    }

    public function on_msg($msg) {
        $this->ajax->add_eval_js('msg("' . addslashes($msg) . '");');
        $this->on_ajax();
    }

    protected function _login() {
        if (Lib_Req::any('ajax')) {
            $this->ajax->popup('login-form');
            $this->on_ajax();
        } else {
            $this->display('login');
        }
    }

    protected function _after_login() {
        $this->ajax->add_innerhtml('#main_box', 'index');
        $this->ajax->add_eval_js('ajax("ctl=Index&act=search&acter_id=' . $this->acter_id . '");');
    }

    protected function _init_acter() {
        if (Lib_Req::any('from_svn')) {
            return ;
        }

        if (!isset($_SESSION[Config_Common::APP_NAME]['acter_id'])) {
            $this->_login();
        }

        $acter_id = $_SESSION[Config_Common::APP_NAME]['acter_id'];

        $Config_Admin = new Config_Admin();
        $role = $Config_Admin->get_acter($acter_id);

        if (!$role) {
            $this->_login();
        }

        $this->_login_session($role);
	}

    protected function _login_session($role) {
        $this->acter_id = $role->id;
        $this->acter = $role;
        $this->view->acter = $role;
        $_SESSION[Config_Common::APP_NAME]['acter_id'] = $role->id;
    }

    protected function _check_pri() {
        if ($this->acter->manager) {
            return ;
        }
        
        $this->on_msg('没有权限');
    }


    protected function write_log($content, $operator) {
        $t = new O_Log();
        $t->acter_id = $this->acter->id;
        $t->operator = $operator;
        $t->content = $content;
        $t->insert();
    }

    protected function must_same_operator($operator) {
        if ($this->acter->operator) {
            if (!$operator || $operator != $this->acter->operator) {
                $this->on_msg('没有权限');
            }
        }
    }
}
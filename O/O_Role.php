<?php
class O_Role {
    public $id = '';
    public $svn_name = '';

    public $passwd = '';

    public $manager = 0;

    public function is_super_admin() {
        return 'admin' == $this->id;
    }
}
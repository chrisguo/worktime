<?php
class Pro_Db {
    static private $_mysql = null;
    static public function get_mysql() {
        if (!self::$_mysql) {
            self::$_mysql = new Lib_Mysql(Config_Common::$db);
        }

        return self::$_mysql;
    }
}
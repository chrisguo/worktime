<?php
class Lib_Req {
    public static function get($col, $default = null) {
        return isset($_GET[$col]) ? $_GET[$col] : $default;
    }

    public static function post($col, $default = null) {
        return isset($_POST[$col]) ? $_POST[$col] : $default;
    }

    public static function cookie($col, $default = null) {
        return isset($_COOKIE[$col]) ? $_COOKIE[$col] : $default;
    }

    public static function any($col, $default = null) {
        if (isset($_GET[$col])) {
            return $_GET[$col];
        } elseif (isset($_POST[$col])) {
            return $_POST[$col];
        } else {
            return $default;
        }
    }
}
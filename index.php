<?php
date_default_timezone_set('Asia/Chongqing');
define('RUNTIME', $_SERVER['REQUEST_TIME']);
define('TODAY', date('Ymd', RUNTIME));
define('ROOT_DIR', dirname(__FILE__) . '/');

define('IS_AJAX', Lib_Req::any('ajax'));
if (isset($argv[0])) {
    define('FROM_SHELL', true);
    $_GET['ctl'] = $argv[1];
    $_GET['act'] = $argv[2];
} else {
    define('FROM_SHELL', false);
}

function __autoload($class) {
    $class_exploded = explode('_', $class);
    $class_exploded[count($class_exploded) - 1] = $class;
    include ROOT_DIR . implode('/', $class_exploded) . '.php';
}

error_reporting(E_ALL);

session_start();

@set_magic_quotes_runtime(0);
unset($GLOBALS, $_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);

$params = array('ctl'=>'Index', 'act'=>'index');
foreach ($params as $param => $param_val_default) {
    $param_val = Lib_Req::any($param);

    $param_val = empty($param_val) ? $param_val_default : $param_val;
    define(strtoupper($param), $param_val);
}


$class = 'Ctl_' . CTL;
$controller = new $class();
$controller->{ACT}();
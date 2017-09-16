<?php

define('CORE_PATH', realpath(dirname(__FILE__)));

require_once(CORE_PATH.'/config.php');
require_once(CORE_PATH.'../vendor/autoload.php');

spl_autoload_register(function ($class) {
    $classPath = CORE_PATH.'/classes/'.$class.'.php';

    if(file_exists($classPath)) {
        include $classPath;
    }
});

<?php

define('CORE_PATH', realpath(dirname(__FILE__)));

require_once(CORE_PATH.'/../vendor/autoload.php');

spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $classPath = CORE_PATH.'/src/'.$class.'.php';

    if(file_exists($classPath)) {
        include $classPath;
    }
});

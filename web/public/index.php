<?php

require_once __DIR__ . '/../../vendor/autoload.php';

switch ($_SERVER['REQUEST_URI']) {
    case "/":
        require_once("../frontend/main.php");
        break;
    case "/new":
        require_once("../frontend/new.php");
        break;
    default:
        require_once("../frontend/logview.php");
        break;
}

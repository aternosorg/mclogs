<?php

require_once("../../core/core.php");

switch ($_SERVER['REQUEST_URI']) {
    case "/":
        require_once("../frontend/main.php");
        break;
    default:
        require_once("../frontend/logview.php");
        break;
}

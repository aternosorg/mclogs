<?php

require_once("../../core/core.php");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading/trailing slashes
$path = trim($path, "/");

switch ($path) {
    case "":
        require_once("../frontend/main.php");
        break;
    default:
        // Store the log ID in a variable for logview.php
        $logId = $path;
        require("../frontend/logview.php");
        break;
}

<?php

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Api\Response\ApiError;

require_once __DIR__ . '/../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Accept-Encoding: " . implode(",", ContentParser::getSupportedEncodings()));

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

switch ($_SERVER['REQUEST_URI']) {
    case "/":
        require_once("../frontend/main.php");
        break;
    case "/1/log":
    case "/1/log/":
        require_once("../endpoints/create.php");
        break;
    case "/1/analyse":
    case "/1/analyse/":
        require_once("../endpoints/analyse.php");
        break;
    case "/1/errors/rate":
        require_once("../endpoints/rate-error.php");
        break;
    case "/1/limits":
        require_once("../endpoints/limits.php");
        break;
    default:
        if (str_starts_with($_SERVER['REQUEST_URI'], "/1/raw/")) {
            require_once("../endpoints/raw.php");
            break;
        }
        if (str_starts_with($_SERVER['REQUEST_URI'], "/1/insights/")) {
            require_once("../endpoints/insights.php");
            break;
        }
        if (str_starts_with($_SERVER['REQUEST_URI'], "/1/log/")) {
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                require_once("../endpoints/delete.php");
            } else {
                require_once("../endpoints/info.php");
            }
            break;
        }

        $error = new ApiError(404, "Could not find endpoint.");
        $error->output();
}

<?php

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Util\URL;

header('Access-Control-Allow-Origin: ' . URL::getBase()->toString());
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');
header("Accept-Encoding: " . implode(",", ContentParser::getSupportedEncodings()));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$setTokenCookie = true;
require_once("../../api/endpoints/create.php");
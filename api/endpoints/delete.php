<?php

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;

$urlId = substr($_SERVER['REQUEST_URI'], strlen("/1/log/"));
$authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
$parts = explode(" ", $authorizationHeader);
$headerToken = $parts[1] ?? null;

if (!$headerToken) {
    $error = new ApiError(401, "Authorization token missing.");
    $error->output();
    exit;
}

$id = new Id($urlId);
$log = Log::find($id);

if(!$log) {
    $error = new ApiError(404, "Log not found.");
    $error->output();
    exit;
}

$token = $log->getToken();
if (!$token || !$token->matches($headerToken)) {
    $error = new ApiError(403, "Invalid authorization token.");
    $error->output();
    exit;
}

$deleted = $log->delete();
if (!$deleted) {
    $error = new ApiError(500, "Failed to delete log.");
    $error->output();
    exit;
}

new ApiResponse()->output();




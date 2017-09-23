<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

http_response_code(429);

$out = new stdClass();
$out->success = false;
$out->error = "Unfortunately you have exceeded the rate limit for the current time period. Please try again later.";
echo json_encode($out);
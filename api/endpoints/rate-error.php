<?php

/* This will be delivered by cloudflare */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$out = new stdClass();
$out->success = false;
$out->error = "Unfortunately you have exceeded the rate limit for the current time period. Please try again later.";
echo json_encode($out);
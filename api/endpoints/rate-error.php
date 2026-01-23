<?php

/* This will be delivered by cloudflare */

use Aternos\Mclogs\Api\Response\ApiError;

$error = new ApiError(
    429,
    "Unfortunately you have exceeded the rate limit for the current time period. Please try again later."
);
$error->output();

<?php

namespace IndifferentKetchup\IBLogs\Api\Response;

use IndifferentKetchup\IBLogs\Log;

class RawLogResponse extends ApiResponse
{
    public function __construct(
        protected Log  $log)
    {
    }

    public function output(): static
    {
        header('Content-Type: text/plain');
        echo $this->log->getContent();

        return $this;
    }

}
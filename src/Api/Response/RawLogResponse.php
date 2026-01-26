<?php

namespace Aternos\Mclogs\Api\Response;

use Aternos\Mclogs\Log;

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
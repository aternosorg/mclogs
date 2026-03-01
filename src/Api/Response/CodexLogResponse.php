<?php

namespace IndifferentKetchup\IBLogs\Api\Response;

use IndifferentKetchup\Codex\Log\LogInterface;

class CodexLogResponse extends ApiResponse
{
    public function __construct(protected LogInterface $codexLog)
    {
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), $this->codexLog->jsonSerialize());
    }
}
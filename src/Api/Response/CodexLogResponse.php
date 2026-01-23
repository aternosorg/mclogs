<?php

namespace Aternos\Mclogs\Api\Response;

use Aternos\Codex\Log\LogInterface;

class CodexLogResponse extends ApiResponse
{
    protected bool $success = true;

    public function __construct(protected LogInterface $codexLog)
    {
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), $this->codexLog->jsonSerialize());
    }
}
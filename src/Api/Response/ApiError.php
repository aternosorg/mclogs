<?php

namespace Aternos\Mclogs\Api\Response;

class ApiError extends ApiResponse
{
    protected bool $success = false;

    public function __construct(
        int              $httpCode,
        protected string $message,
    )
    {
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        $data['message'] = $this->message;
        return $data;
    }
}

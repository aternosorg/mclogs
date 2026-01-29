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
        $this->setHttpCode($httpCode);
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        $data['error'] = $this->message;
        return $data;
    }
}

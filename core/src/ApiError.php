<?php

class ApiError implements JsonSerializable
{
    public function __construct(
        protected int $httpCode,
        protected string $message,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'success' => false,
            'error' => $this->message,
        ];
    }

    /**
     * Output this error as a JSON response and terminate the script
     * @return never
     */
    public function output(): never
    {
        header('Content-Type: application/json');
        http_response_code($this->httpCode);
        echo json_encode($this);
        exit;
    }
}

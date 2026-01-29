<?php

namespace Aternos\Mclogs\Api\Response;

class ApiResponse implements \JsonSerializable
{
    protected int $httpCode = 200;
    protected bool $success = true;

    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
        ];
    }

    /**
     * @param int $httpCode
     * @return $this
     */
    public function setHttpCode(int $httpCode): static
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    /**
     * @param bool $success
     * @return $this
     */
    public function setSuccess(bool $success): static
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @return $this
     */
    public function output(): static
    {
        header('Content-Type: application/json');
        http_response_code($this->httpCode);
        echo json_encode($this, JSON_UNESCAPED_SLASHES);
        return $this;
    }
}

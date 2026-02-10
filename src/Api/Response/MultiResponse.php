<?php

namespace Aternos\Mclogs\Api\Response;

class MultiResponse extends ApiResponse
{
    protected int $httpCode = 207;

    /**
     * @var ApiResponse[]
     */
    protected array $responses = [];

    /**
     * @param string $id
     * @param ApiResponse $response
     * @return $this
     */
    public function addResponse(string $id, ApiResponse $response): static
    {
        $this->responses[$id] = $response;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $response = parent::jsonSerialize();
        $results = [];
        foreach ($this->responses as $id => $apiResponse) {
            $result = $apiResponse->jsonSerialize();
            $result["id"] = $id;
            $result["status"] = $apiResponse->getHttpCode();
            $results[] = $result;
        }
        $response["results"] = $results;
        return $response;
    }
}

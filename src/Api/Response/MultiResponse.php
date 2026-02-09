<?php

namespace Aternos\Mclogs\Api\Response;

class MultiResponse extends ApiResponse
{
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
        $httpCode = $response->getHttpCode();

        if (count($this->responses) === 0) {
            $this->setHttpCode($httpCode);
            $this->setSuccess($response->isSuccess());
        } else {
            if ($this->getHttpCode() !== $httpCode) {
                $this->setHttpCode(207);
            }
            if (!$this->isSuccess() && $response->isSuccess()) {
                $this->setSuccess(true);
            }
        }

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

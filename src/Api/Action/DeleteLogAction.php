<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

class DeleteLogAction extends ApiAction
{
    protected function getRequestToken(): ?string
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$authorizationHeader) {
            return null;
        }
        $parts = explode(" ", $authorizationHeader);
        return $parts[1] ?? null;
    }

    /**
     * @return ApiResponse
     */
    protected function runApi(): ApiResponse
    {
        $requestToken = $this->getRequestToken();

        if (!$requestToken) {
            return new ApiError(400, "Missing token.");
        }

        $id = new Id(URL::getLastPathPart());
        $log = Log::find($id);

        if (!$log) {
            return new ApiError(404, "Log not found.");
        }

        $token = $log->getToken();
        if (!$token || !$token->matches($requestToken)) {
            return new ApiError(403, "Invalid token.");
        }

        $deleted = $log->delete();
        if (!$deleted) {
            return new ApiError(500, "Failed to delete log.");
        }

        $this->handleDeletedLog($log);

        return new ApiResponse();
    }

    protected function handleDeletedLog(Log $log): void
    {

    }
}
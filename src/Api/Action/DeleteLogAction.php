<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\Response\ApiError;
use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;
use IndifferentKetchup\IBLogs\Id;
use IndifferentKetchup\IBLogs\Log;
use IndifferentKetchup\IBLogs\Util\URL;

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

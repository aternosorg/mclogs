<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\MultiResponse;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;

class BulkDeleteLogsAction extends ApiAction
{
    protected const int MAX_IDS = 256;

    /**
     * @return ApiResponse
     */
    protected function runApi(): ApiResponse
    {
        $data = new ContentParser()->getContent();
        if ($data instanceof ApiError) {
            return $data;
        }

        if (count($data) === 0) {
            return new ApiError(400, "No logs provided.");
        }
        if (count($data) > static::MAX_IDS) {
            return new ApiError(400, "Too many logs. Maximum is " . static::MAX_IDS . ".");
        }

        foreach ($data as $log) {
            if (!isset($log["id"]) || !is_string($log["id"]) ||
                !preg_match("/" . Id::PATTERN . "/", $log["id"])) {
                return new ApiError(400, "Each log must have a valid 'id' field.");
            }
            if (!isset($log["token"]) || !is_string($log["token"])) {
                return new ApiError(400, "Each log must have a valid 'token' field.");
            }
        }

        $response = new MultiResponse();
        foreach ($data as $log) {
            $id = $log["id"];
            $token = $log["token"];

            $log = Log::find(new Id($id));
            if (!$log) {
                $response->addResponse($id, new ApiError(404, "Log not found."));
                continue;
            }

            $logToken = $log->getToken();
            if (!$logToken || !$logToken->matches($token)) {
                $response->addResponse($id, new ApiError(403, "Invalid token."));
                continue;
            }

            $deleted = $log->delete();
            if (!$deleted) {
                $response->addResponse($id, new ApiError(500, "Failed to delete log."));
                continue;
            }

            $response->addResponse($id, new ApiResponse());
        }

        return $response;
    }
}

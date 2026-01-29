<?php

namespace Aternos\Mclogs\Api;

use Aternos\Mclogs\Api\Response\ApiError;

/**
 * Utility class for reading log content from the http request
 */
class ContentParser
{
    /**
     * Get all supported content encodings
     * @return string[]
     */
    public static function getSupportedEncodings(): array
    {
        return ["deflate", "gzip", "x-gzip"];
    }

    /**
     * Get the content from the http request
     *
     * @return string|ApiError The content string or an ApiError on failure
     */
    public function getContent(): array|ApiError
    {
        $body = file_get_contents('php://input');
        if ($body === false) {
            return new ApiError(500, "Failed to read request body.");
        }

        $encodingHeader = $_SERVER['HTTP_CONTENT_ENCODING'] ?? '';
        if ($encodingHeader) {
            $encodingSteps = explode(',', $encodingHeader);
            foreach (array_reverse($encodingSteps) as $step) {
                switch (trim(strtolower($step))) {
                    case "deflate":
                        $body = gzinflate($body);
                        break;
                    case "x-gzip":
                    case "gzip":
                        $body = gzdecode($body);
                        break;
                    default:
                        return new ApiError(415, "Unsupported Content-Encoding: " . htmlspecialchars($step));
                }
                if ($body === false) {
                    return new ApiError(400, "Failed to decode request body with encoding: " . htmlspecialchars($step));
                }
            }
        }

        $contentTypeHeader = $_SERVER['CONTENT_TYPE'] ?? '';
        switch ($contentTypeHeader) {
            case "application/x-www-form-urlencoded":
                parse_str($body, $data);
                break;
            case "application/json":
                $data = json_decode($body, true);
                if (!is_array($data)) {
                    return new ApiError(400, "Failed to parse JSON body.");
                }
                break;
            default:
                return new ApiError(415, "Unsupported Content-Type: " . htmlspecialchars($contentTypeHeader));
        }

        if (!isset($data['content'])) {
            return new ApiError(400, "Required field 'content' not found.");
        }

        if (empty($data['content'])) {
            return new ApiError(400, "Required field 'content' is empty.");
        }

        if (!is_string($data['content'])) {
            return new ApiError(400, "Field 'content' must be a string.");
        }

        return $data;
    }
}

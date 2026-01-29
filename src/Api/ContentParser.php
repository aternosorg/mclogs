<?php

namespace Aternos\Mclogs\Api;

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

/**
 * Utility class for reading log content from the http request
 */
class ContentParser
{
    protected const int MAX_ENCODING_STEPS = 5;

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
     * @return array|ApiError An array with the content or an ApiError on failure
     */
    public function getContent(): array|ApiError
    {
        $limit = Config::getInstance()->get(ConfigKey::STORAGE_LIMIT_BYTES) * 2;
        $body = file_get_contents('php://input', length: $limit + 1);
        if ($body === false) {
            return new ApiError(500, "Failed to read request body.");
        }
        if (strlen($body) > $limit) {
            return new ApiError(413, "Request body exceeds maximum allowed size.");
        }

        $encodingHeader = $_SERVER['HTTP_CONTENT_ENCODING'] ?? '';
        if ($encodingHeader) {
            $encodingSteps = explode(',', $encodingHeader);
            if (count($encodingSteps) > static::MAX_ENCODING_STEPS) {
                return new ApiError(400, "Too many Content-Encoding steps.");
            }
            foreach (array_reverse($encodingSteps) as $step) {
                switch (trim(strtolower($step))) {
                    case "deflate":
                        $body = @gzinflate($body, $limit);
                        break;
                    case "x-gzip":
                    case "gzip":
                        $body = @gzdecode($body, $limit);
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
        if ($pos = strpos($contentTypeHeader, ';')) {
            $contentTypeHeader = substr($contentTypeHeader, 0, $pos);
        }
        switch ($contentTypeHeader) {
            case "application/x-www-form-urlencoded":
                parse_str($body, $data);
                break;
            case "application/json":
                $data = @json_decode($body, true);
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

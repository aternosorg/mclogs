<?php

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
    public function getContent(): string|ApiError
    {
        $body = file_get_contents('php://input');
        if ($body === false) {
            return new ApiError(500, "Failed to read request body.");
        }

        $encodingHeader = $_SERVER['HTTP_CONTENT_ENCODING'] ?? '';
        if ($encodingHeader) {
            $encodingSteps = explode(',', $encodingHeader);
            foreach (array_reverse($encodingSteps) as $step) {
                switch (strtolower($step)) {
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
            }
        }

        parse_str($body, $data);
        if (!isset($data['content'])) {
            return new ApiError(400, "Required POST argument 'content' not found.");
        }

        if (empty($data['content'])) {
            return new ApiError(400, "Required POST argument 'content' is empty.");
        }

        return $data['content'];
    }
}

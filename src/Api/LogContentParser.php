<?php

namespace Aternos\Mclogs\Api;

use Aternos\Mclogs\Api\Response\ApiError;

class LogContentParser extends ContentParser
{
    /**
     * @inheritDoc
     */
    public function getContent(): array|ApiError
    {
        $data = parent::getContent();
        if ($data instanceof ApiError) {
            return $data;
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

<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Api\ContentParser;
use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\LogResponse;
use Aternos\Mclogs\Data\MetadataEntry;
use Aternos\Mclogs\Log;

class CreateLogAction extends ApiAction
{
    protected bool $includeCookie = false;
    protected bool $includeToken = true;

    public function runApi(): ApiResponse
    {
        $data = new ContentParser()->getContent();

        if ($data instanceof ApiError) {
            return $data;
        }

        $content = $data['content'];
        $metadata = [];
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $metadata = MetadataEntry::allFromArray($data['metadata']);
        }
        $source = null;
        if (isset($data['source']) && is_string($data['source'])) {
            $source = $data['source'];
        }

        $log = Log::create($content, $metadata, $source);

        if ($this->includeCookie) {
            $log->setTokenCookie();
        }

        return new LogResponse($log, $this->includeToken);
    }
}
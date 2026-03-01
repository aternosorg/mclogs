<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Api\LogContentParser;
use IndifferentKetchup\IBLogs\Api\Response\ApiError;
use IndifferentKetchup\IBLogs\Api\Response\ApiResponse;
use IndifferentKetchup\IBLogs\Api\Response\LogResponse;
use IndifferentKetchup\IBLogs\Data\MetadataEntry;
use IndifferentKetchup\IBLogs\Log;

class CreateLogAction extends ApiAction
{
    protected bool $includeCookie = false;
    protected bool $includeToken = true;

    public function runApi(): ApiResponse
    {
        $data = new LogContentParser()->getContent();

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

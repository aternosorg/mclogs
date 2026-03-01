<?php

namespace IndifferentKetchup\IBLogs\Api\Response;

use IndifferentKetchup\IBLogs\Config\Config;
use IndifferentKetchup\IBLogs\Config\ConfigKey;

class LimitsResponse extends ApiResponse
{
    public function jsonSerialize(): array
    {
        $config = Config::getInstance();
        $data = parent::jsonSerialize();
        $data['storageTime'] = $config->get(ConfigKey::STORAGE_TTL);
        $data['maxLength'] = $config->get(ConfigKey::STORAGE_LIMIT_BYTES);
        $data['maxLines'] = $config->get(ConfigKey::STORAGE_LIMIT_LINES);
        return $data;
    }
}
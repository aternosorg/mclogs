<?php

namespace Aternos\Mclogs\Api\Response;

use Aternos\Mclogs\Log;

class LogCreatedResponse extends ApiResponse
{
    public function __construct(protected Log $log)
    {
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        $data['id'] = $this->log->getId();
        $data['url'] = $this->log->getUrl()->toString();
        $data['raw'] = $this->log->getRawURL()->toString();
        $data['expires'] = $this->log->getExpires()->toDateTime()->getTimestamp();
        $data['token'] = $this->log->getToken();
        return $data;
    }

}
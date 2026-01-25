<?php

namespace Aternos\Mclogs\Api\Response;

use Aternos\Mclogs\Log;

class LogResponse extends ApiResponse
{
    public function __construct(protected Log $log, protected bool $withToken = false)
    {
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        $data['id'] = $this->log->getId();
        $data['source'] = $this->log->getSource();
        $data['created'] = $this->log->getCreated()?->toDateTime()->getTimestamp();
        $data['expires'] = $this->log->getExpires()?->toDateTime()->getTimestamp();
        $data['url'] = $this->log->getUrl()->toString();
        $data['raw'] = $this->log->getRawURL()->toString();
        if ($this->withToken) {
            $data['token'] = $this->log->getToken();
        }
        $data['metadata'] = $this->log->getMetadata();
        return $data;
    }
}
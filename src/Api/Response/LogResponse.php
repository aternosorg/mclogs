<?php

namespace Aternos\Mclogs\Api\Response;

use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;

class LogResponse extends ApiResponse
{
    public function __construct(
        protected Log  $log,
        protected bool $withToken = false,
        protected bool $withInsights = false,
        protected bool $withRaw = false,
        protected bool $withParsed = false)
    {
        $this->loadFromGet();
    }

    public function loadFromGet(): static
    {
        $url = URL::getCurrent();
        $query = $url->getQuery();
        if (empty($query)) {
            return $this;
        }
        parse_str($url->getQuery(), $get);
        $this->withInsights = isset($get['insights']);
        $this->withRaw = isset($get['raw']);
        $this->withParsed = isset($get['parsed']);
        return $this;
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        $data['id'] = $this->log->getId();
        $data['source'] = $this->log->getSource();
        $data['created'] = $this->log->getCreated()?->toDateTime()->getTimestamp();
        $data['expires'] = $this->log->getExpires()?->toDateTime()->getTimestamp();
        $data['size'] = $this->log->getSize();
        $data['lines'] = $this->log->getLineCount();
        $data['errors'] = $this->log->getErrorCount();
        $data['url'] = $this->log->getUrl()->toString();
        $data['raw'] = $this->log->getRawURL()->toString();
        if ($this->withToken) {
            $data['token'] = $this->log->getToken();
        }
        $data['metadata'] = $this->log->getMetadata();
        if ($this->withInsights || $this->withRaw || $this->withParsed) {
            $data['content'] = [];
        }
        if ($this->withInsights) {
            $data['content']['insights'] = $this->log->getCodexLog()->setIncludeEntries(false);
        }
        if ($this->withRaw) {
            $data['content']['raw'] = $this->log->getContent();
        }
        if ($this->withParsed) {
            $data['content']['parsed'] = $this->log->getCodexLog()->getEntries();
        }
        return $data;
    }
}
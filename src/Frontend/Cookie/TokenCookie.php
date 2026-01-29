<?php

namespace Aternos\Mclogs\Frontend\Cookie;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Log;

class TokenCookie extends Cookie
{
    /**
     * @param Log $log
     * @return $this
     */
    public function setLog(Log $log): static
    {
        $this->log = $log;
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getKey(): string
    {
        return "MCLOGS_LOG_TOKEN";
    }

    /**
     * @param Log|null $log
     */
    public function __construct(protected ?Log $log = null)
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        if (!$this->log) {
            return "/";
        }
        return "/" . $this->log->getId()->get();
    }

    protected function getMaxAge(): ?int
    {
        return Config::getInstance()->get(ConfigKey::STORAGE_TTL);
    }
}
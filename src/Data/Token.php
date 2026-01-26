<?php

namespace Aternos\Mclogs\Data;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Util\URL;
use Random\RandomException;

class Token implements \JsonSerializable
{
    protected const string COOKIE_NAME = "MCLOGS_LOG_TOKEN";

    public function __construct(protected ?string $value = null)
    {
        if ($this->value === null) {
            $this->generate();
        }
    }

    /**
     * @param string $token
     * @return bool
     */
    public function matches(string $token): bool
    {
        return hash_equals($this->value, $token);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * @throws RandomException
     */
    protected function generate(): void
    {
        $this->value = bin2hex(random_bytes(32));
    }

    public function get(): ?string
    {
        return $this->value;
    }

    /**
     * @param Log $log
     * @return bool
     */
    public function setCookie(Log $log): bool
    {
        $domain = URL::getBase()->getHost();
        if ($domain === "localhost") {
            $domain = false;
        }

        return setcookie(
            static::COOKIE_NAME,
            $this->get(),
            [
                'expires' => time() + Config::getInstance()->get(ConfigKey::STORAGE_TTL),
                'path' => "/" . $log->getId()->get(),
                'domain' => $domain,
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }
}
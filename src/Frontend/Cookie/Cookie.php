<?php

namespace Aternos\Mclogs\Frontend\Cookie;

use Aternos\Mclogs\Util\URL;

abstract class Cookie
{
    protected ?string $value = null;

    /**
     * @return string
     */
    abstract protected function getKey(): string;

    /**
     * @return string
     */
    protected function getDomain(): string
    {
        return "";
    }

    /**
     * @return int|null
     */
    protected function getMaxAge(): ?int
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return "/";
    }

    /**
     * @return bool
     */
    protected function isSecure(): bool
    {
        return URL::getCurrent()->getScheme() === "https";
    }

    /**
     * @return bool
     */
    protected function isHttpOnly(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    protected function getSameSite(): string
    {
        return "Lax";
    }

    protected function __construct()
    {
        $this->value = $_COOKIE[$this->getKey()] ?? null;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function set(string $value): bool
    {
        $options = [
            'expires' => $this->getMaxAge() !== null ? time() + $this->getMaxAge() : 0,
            'path' => $this->getPath(),
            'domain' => $this->getDomain(),
            'secure' => $this->isSecure(),
            'httponly' => $this->isHttpOnly(),
            'samesite' => $this->getSameSite()
        ];

        $result = setcookie(
            $this->getKey(),
            $value,
            $options
        );

        if ($result) {
            $this->value = $value;
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $options = [
            'expires' => time() - 3600,
            'path' => $this->getPath(),
            'domain' => $this->getDomain(),
            'secure' => $this->isSecure(),
            'httponly' => $this->isHttpOnly(),
            'samesite' => $this->getSameSite()
        ];

        $result = setcookie(
            $this->getKey(),
            '',
            $options
        );

        if ($result) {
            $this->value = null;
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->getValue() !== null;
    }
}
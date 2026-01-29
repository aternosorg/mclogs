<?php

namespace Aternos\Mclogs\Data;

use Random\RandomException;

class Token implements \JsonSerializable
{
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
}
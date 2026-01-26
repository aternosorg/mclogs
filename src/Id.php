<?php

namespace Aternos\Mclogs;

use Aternos\Mclogs\Config\ConfigKey;

class Id implements \JsonSerializable
{
    public const string PATTERN = '[a-zA-Z0-9]+';
    protected const string CHARACTERS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

    /**
     * @param string|null $id
     */
    public function __construct(protected ?string $id = null)
    {
        if ($this->id === null) {
            $this->generate();
        }
    }

    /**
     * Generates a new id
     *
     * @return string
     */
    protected function generate(): string
    {
        $config = \Aternos\Mclogs\Config\Config::getInstance();
        $idLength = $config->get(ConfigKey::ID_LENGTH);

        $newId = "";
        for ($i = 0; $i < $idLength; $i++) {
            $newId .= static::CHARACTERS[rand(0, strlen(static::CHARACTERS) - 1)];
        }

        return $this->id = $newId;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }

    public function jsonSerialize(): string
    {
        return $this->id;
    }
}
<?php

namespace Aternos\Mclogs;

use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Storage\Backend\StorageBackendId;

class Id
{
    protected const string CHARACTERS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

    protected ?string $fullId = null;
    protected ?StorageBackendId $storageBackendId = null;
    protected string $rawId;

    /**
     * Id constructor.
     *
     * If id is known, pass it here, if you want
     * to generate a new id, pass nothing
     *
     * @param string|null $fullId
     * @param StorageBackendId|null $storageBackendId
     */
    public function __construct(?string $fullId = null, ?StorageBackendId $storageBackendId = null)
    {
        if ($storageBackendId !== null) {
            $this->setStorageBackendId($storageBackendId);
        }

        if ($fullId === null) {
            $this->generateNew();
        } else {
            $this->fullId = $fullId;
            $this->decode();
        }
    }

    /**
     * Regenerates the rawId to generate a new id
     *
     * @return string
     */
    public function generateNew(): string
    {
        $config = \Aternos\Mclogs\Config\Config::getInstance();
        $idLength = $config->get(ConfigKey::ID_LENGTH);

        $rawId = "";
        for ($i = 0; $i < $idLength; $i++) {
            $rawId .= static::CHARACTERS[rand(0, strlen(static::CHARACTERS) - 1)];
        }

        $this->rawId = $rawId;
        $this->fullId = null;
        return $rawId;
    }

    /**
     * Set the storage id
     *
     * @param StorageBackendId $storageBackendId
     * @return static
     */
    public function setStorageBackendId(StorageBackendId $storageBackendId): static
    {
        $this->storageBackendId = $storageBackendId;
        return $this;
    }

    /**
     * Get the storage id
     *
     * @return StorageBackendId
     */
    public function getStorageBackendId(): StorageBackendId
    {
        return $this->storageBackendId;
    }

    /**
     * Get the raw id, used by storage
     *
     * @return string
     */
    public function getRaw(): string
    {
        return $this->rawId;
    }

    /**
     * Get the full id, will be generated from rawId and storageId if necessary
     *
     * @return string
     */
    public function get(): string
    {
        $chars = str_split(static::CHARACTERS);

        if ($this->fullId === null) {
            $index = array_search($this->storageBackendId->value, $chars);
            foreach (str_split($this->rawId) as $rawIdPart) {
                $index += array_search($rawIdPart, $chars);
            }

            $encodedStorageId = $chars[$index % count($chars)];
            $this->fullId = $encodedStorageId . $this->rawId;
        }

        return $this->fullId;
    }

    /**
     * Decode a full id to rawId and storageId
     *
     * @return bool
     */
    protected function decode(): bool
    {
        if (!$this->isValid()) {
            return false;
        }
        $chars = str_split(static::CHARACTERS);

        $this->rawId = substr($this->fullId, 1);
        $encodedStorageId = substr($this->fullId, 0, 1);

        $index = array_search($encodedStorageId, $chars) + strlen($this->rawId) * count($chars);
        foreach (str_split($this->rawId) as $rawIdPart) {
            $index -= array_search($rawIdPart, $chars);
        }

        if ($storageId = StorageBackendId::tryFrom($chars[$index % count($chars)])) {
            $this->storageBackendId = $storageId;
        }
        return true;
    }

    /**
     * Check if the id matches the expected format
     *
     * @return bool
     */
    protected function isValid(): bool
    {
        $config = \Aternos\Mclogs\Config\Config::getInstance();
        $idLength = $config->get(ConfigKey::ID_LENGTH);

        $expectedLength = $idLength + 1;
        if (strlen($this->fullId) !== $expectedLength) {
            return false;
        }

        $expectedChars = str_split(static::CHARACTERS);
        $chars = str_split($this->fullId);
        foreach ($chars as $char) {
            if (!in_array($char, $expectedChars)) {
                return false;
            }
        }
        return true;
    }
}
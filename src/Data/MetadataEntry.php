<?php

namespace Aternos\Mclogs\Data;

class MetadataEntry implements \JsonSerializable
{
    protected const int MAX_KEY_LENGTH = 64;
    protected const int MAX_LABEL_LENGTH = 128;
    protected const int MAX_VALUE_LENGTH = 1024;

    protected ?string $key = null;
    protected mixed $value = null;
    protected ?string $label = null;
    protected bool $visible = true;

    /**
     * @param array|null $dataArray
     * @return MetadataEntry[]
     */
    public static function allFromObjectArray(?array $dataArray): array
    {
        if ($dataArray === null) {
            return [];
        }
        $entries = [];
        foreach ($dataArray as $data) {
            $entries[] = static::fromObject($data);
        }
        return $entries;
    }

    /**
     * @param object $data
     * @return MetadataEntry
     */
    public static function fromObject(object $data): MetadataEntry
    {
        $entry = new MetadataEntry();
        if (isset($data->key) && is_string($data->key)) {
            $entry->setKey($data->key);
        }
        if (isset($data->value)) {
            $entry->setValue($data->value);
        }
        if (isset($data->label) && is_string($data->label)) {
            $entry->setLabel($data->label);
        }
        if (isset($data->visible) && is_bool($data->visible)) {
            $entry->setVisible($data->visible);
        }
        return $entry;
    }

    public function jsonSerialize(): array
    {
        return [
            "key" => $this->key,
            "value" => $this->value,
            "label" => $this->label,
            "visible" => $this->visible,
        ];
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): static
    {
        if ($key && strlen($key) > static::MAX_KEY_LENGTH) {
            $key = substr($key, 0, static::MAX_KEY_LENGTH);
        }
        $this->key = $key;
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue(mixed $value): static
    {
        if (is_string($value)) {
            if (strlen($value) > static::MAX_VALUE_LENGTH) {
                $value = substr($value, 0, static::MAX_VALUE_LENGTH);
            }
            $this->value = $value;
            return $this;
        }
        if (is_int($value) || is_float($value) || is_bool($value) || is_null($value)) {
            $this->value = $value;
            return $this;
        }
        $encodedValue = @json_encode($value);
        if ($encodedValue === false) {
            $this->value = null;
            return $this;
        }
        if (strlen($encodedValue) > static::MAX_VALUE_LENGTH) {
            $encodedValue = substr($encodedValue, 0, static::MAX_VALUE_LENGTH);
        }
        $this->value = $encodedValue;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        if ($label && strlen($label) > static::MAX_LABEL_LENGTH) {
            $label = substr($label, 0, static::MAX_LABEL_LENGTH);
        }
        $this->label = $label;
        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;
        return $this;
    }
}
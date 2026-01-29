<?php

namespace Aternos\Mclogs\Data;

use MongoDB\BSON\Serializable;
use MongoDB\Model\BSONDocument;

class MetadataEntry implements \JsonSerializable, Serializable
{
    public const int MAX_ENTRIES = 100;
    protected const int MAX_KEY_LENGTH = 64;
    protected const int MAX_LABEL_LENGTH = 128;
    protected const int MAX_VALUE_LENGTH = 1024;

    protected ?string $key = null;
    protected mixed $value = null;
    protected ?string $label = null;
    protected bool $visible = true;

    /**
     * @param iterable|null $dataArray
     * @return MetadataEntry[]
     */
    public static function allFromArray(?iterable $dataArray): array
    {
        if ($dataArray === null) {
            return [];
        }
        $entries = [];
        foreach ($dataArray as $data) {
            if (is_array($data)) {
                $entry = static::fromArray($data);
            } else if (is_object($data)) {
                $entry = static::fromObject($data);
            } else {
                continue;
            }
            if ($entry !== null) {
                $entries[] = $entry;
            }
            if (count($entries) >= static::MAX_ENTRIES) {
                break;
            }
        }
        return $entries;
    }

    /**
     * @param array $data
     * @return MetadataEntry|null
     */
    public static function fromArray(array $data): ?MetadataEntry
    {
        $entry = new MetadataEntry()->setFromArray($data);
        if (!$entry->isValid()) {
            return null;
        }
        return $entry;
    }

    /**
     * @param object $data
     * @return MetadataEntry|null
     */
    public static function fromObject(object $data): ?MetadataEntry
    {
        if ($data instanceof BSONDocument) {
            $arrayData = $data->getArrayCopy();
        } else {
            $arrayData = get_object_vars($data);
        }
        return static::fromArray($arrayData);
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

    public function bsonSerialize(): array
    {
        return $this->jsonSerialize();
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

    public function getDisplayLabel(): ?string
    {
        return $this->label ?? $this->key;
    }

    public function getDisplayValue(): string
    {
        return $this->value;
    }

    public function setLabel(?string $label): static
    {
        if (is_string($label) && strlen($label) > static::MAX_LABEL_LENGTH) {
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

    public function isValid(): bool
    {
        return $this->key !== null && $this->value !== null;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setFromArray(array $data): static
    {
        if (isset($data['key']) && is_string($data['key'])) {
            $this->setKey($data['key']);
        }
        if (isset($data['value'])) {
            $this->setValue($data['value']);
        }
        if (isset($data['label']) && is_string($data['label'])) {
            $this->setLabel($data['label']);
        }
        if (isset($data['visible']) && is_bool($data['visible'])) {
            $this->setVisible($data['visible']);
        }
        return $this;
    }
}

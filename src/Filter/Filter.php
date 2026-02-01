<?php

namespace Aternos\Mclogs\Filter;

abstract class Filter implements \JsonSerializable
{
    /**
     * @var Filter[]|null
     */
    protected static ?array $filter = null;

    /**
     * Get all filters
     *
     * @return Filter[]
     */
    public static function getAll(): array
    {
        if (static::$filter !== null) {
            return static::$filter;
        }
        return static::$filter = [
            new TrimFilter(),
            new LimitBytesFilter(),
            new LimitLinesFilter(),
            new IPv4Filter(),
            new IPv6Filter(),
            new UsernameFilter(),
            new AccessTokenFilter(),
        ];
    }

    /**
     * Filter the $data string with all filters and return it
     *
     * @param string $data
     * @return string
     */
    public static function filterAll(string $data): string
    {
        foreach (static::getAll() as $filter) {
            $data = $filter->filter($data);
        }
        return $data;
    }

    /**
     * @return FilterType
     */
    abstract public function getType(): FilterType;

    /**
     * @return array
     */
    abstract public function getData(): mixed;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "type" => $this->getType()->value,
            "data" => $this->getData(),
        ];
    }

    /**
     * Filter the $data string and return it
     *
     * @param string $data
     * @return string
     */
    abstract public function filter(string $data): string;
}
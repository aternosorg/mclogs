<?php

namespace Aternos\Mclogs\Filter;

abstract class Filter
{
    /**
     * Get all filters
     *
     * @return Filter[]
     */
    public static function getAll(): array
    {
        return [
            new TrimFilter(),
            new LengthFilter(),
            new LinesFilter(),
            new IpFilter(),
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
     * Filter the $data string and return it
     *
     * @param string $data
     * @return string
     */
    abstract public function filter(string $data): string;
}
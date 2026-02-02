<?php

namespace Aternos\Mclogs\Filter;

class TrimFilter extends Filter
{
    /**
     * Filter the $data string and return it
     *
     * Trim pre and after whitespace
     *
     * @param string $data
     * @return string
     */
    public function filter(string $data): string
    {
        return trim($data);
    }

    public function getType(): FilterType
    {
        return FilterType::TRIM;
    }

    public function getData(): object
    {
        return new \stdClass();
    }
}
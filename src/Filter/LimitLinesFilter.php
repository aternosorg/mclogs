<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class LimitLinesFilter extends Filter
{
    /**
     * Filter the $data string and return it
     *
     * Cuts the lines down to maxLines
     *
     * @param string $data
     * @return string
     */
    public function filter(string $data): string
    {
        $linesLimit = Config::getInstance()->get(ConfigKey::STORAGE_LIMIT_LINES);
        return implode("\n", array_slice(explode("\n", $data), 0, $linesLimit));
    }

    /**
     * @return FilterType
     */
    public function getType(): FilterType
    {
        return FilterType::LIMIT_LINES;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            "limit" => Config::getInstance()->get(ConfigKey::STORAGE_LIMIT_LINES)
        ];
    }
}
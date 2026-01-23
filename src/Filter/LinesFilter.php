<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class LinesFilter extends Filter
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
}
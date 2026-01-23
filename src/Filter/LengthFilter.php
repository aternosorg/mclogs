<?php

namespace Aternos\Mclogs\Filter;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

class LengthFilter extends Filter {

    /**
     * Filter the $data string and return it
     *
     * Cuts the length down to maxLength
     *
     * @param string $data
     * @return string
     */
    public function filter(string $data): string
    {
        $lengthLimit = Config::getInstance()->get(ConfigKey::STORAGE_LIMIT_BYTES);
        return mb_strcut($data, 0, $lengthLimit);
    }
}
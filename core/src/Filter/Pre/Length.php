<?php

namespace Filter\Pre;

class Length implements PreFilterInterface {

    /**
     * Filter the $data string and return it
     *
     * Cuts the length down to maxLength
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        $config = \Config::Get('storage');
        return mb_strcut($data, 0, $config['maxLength']);
    }
}
<?php

namespace Filter\Pre;

class Lines implements PreFilterInterface {

    /**
     * Filter the $data string and return it
     *
     * Cuts the lines down to maxLines
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        $config = \Config::Get('storage');
        return implode("\n", array_slice(explode("\n", $data), 0, $config["maxLines"]));
    }
}
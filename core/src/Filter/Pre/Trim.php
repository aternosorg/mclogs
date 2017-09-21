<?php

namespace Filter\Pre;

class Trim implements PreFilterInterface {

    /**
     * Filter the $data string and return it
     *
     * Trim pre and after whitespace
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        return trim($data);
    }
}
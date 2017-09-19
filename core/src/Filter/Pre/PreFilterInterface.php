<?php

namespace Filter\Pre;

interface PreFilterInterface
{
    /**
     * Filter the $data string and return it
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string;
}
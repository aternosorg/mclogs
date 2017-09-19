<?php

namespace Filter\Pre;

class Ip implements PreFilterInterface {

    /**
     * Filter the $data string and return it
     *
     * Searches for IP addresses and censors them
     * Currently only IPv4
     *
     * @param string $data
     * @return string
     */
    public static function Filter(string $data): string
    {
        return preg_replace('/([0-9]{1,3}\.){3}[0-9]{1,3}/m', "**.**.**.**", $data);
    }
}
<?php

class Config {

    /**
     * Get a config array from a config file placed in config/$name.php
     *
     * @param string $name
     * @return array
     */
    public static function Get(string $name) : array
    {
        $config = array();
        require(CORE_PATH."/config/".$name.".php");
        return $config;
    }

}
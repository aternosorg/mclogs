<?php

class Config
{
    /**
     * Get a config array from a config file placed in config/$name.php
     *
     * @param string $name
     * @return array
     */
    public static function Get(string $name): array
    {
        $config = array();
        $configPath = CORE_PATH . "/config/" . $name . ".php";
        if (file_exists($configPath)) {
            require($configPath);
        }
        return $config;
    }

}
<?php

namespace Aternos\Mclogs\Util;

use Uri\Rfc3986\Uri;

class URL
{
    protected const string API_SUBDOMAIN = "api.";

    protected static ?Uri $base = null;
    protected static ?Uri $api = null;
    protected static ?Uri $current = null;

    /**
     * Get base URL
     *
     * @return Uri
     */
    public static function getBase(): Uri
    {
        if (static::$base) {
            return static::$base;
        }
        $host = $_SERVER['HTTP_HOST'];
        if (str_starts_with($host, static::API_SUBDOMAIN)) {
            $host = substr($host, strlen(static::API_SUBDOMAIN));
        }
        return static::$base = new Uri($_SERVER['REQUEST_SCHEME'] . "://" . $host);
    }

    /**
     * Get API URL
     *
     * @return Uri
     */
    public static function getApi(): Uri
    {
        if (static::$api) {
            return static::$api;
        }
        $base = static::getBase();
        return static::$api = $base->withHost(static::API_SUBDOMAIN . $base->getHost());
    }

    /**
     * @return Uri
     */
    public static function getCurrent(): Uri
    {
        if (static::$current) {
            return static::$current;
        }
        $scheme = $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];
        return static::$current = new Uri("$scheme://$host$requestUri");
    }

    /**
     * @return string
     */
    public static function getLastPathPart(): string
    {
        $path = static::getCurrent()->getPath();
        $parts = explode("/", $path);
        do {
            $part = trim(array_pop($parts));
        } while ($part === "" && count($parts) > 0);
        return $part;
    }
}
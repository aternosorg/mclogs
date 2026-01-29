<?php

namespace Aternos\Mclogs\Util;

use Uri\Rfc3986\Uri;

class URL
{
    protected const string API_SUBDOMAIN = "api.";

    protected static ?Uri $base = null;
    protected static ?Uri $api = null;
    protected static ?Uri $current = null;

    public static function clear(): void
    {
        static::$base = null;
        static::$api = null;
        static::$current = null;
    }

    /**
     * @return string
     */
    protected static function readProtocol(): string
    {
        if (isset($_SERVER['HTTP_FORWARDED'])) {
            $forwarded = explode(';', $_SERVER['HTTP_FORWARDED']);
            foreach ($forwarded as $part) {
                $part = trim($part);
                $partParts = explode('=', $part, 2);
                if (count($partParts) === 2 && strtolower($partParts[0]) === 'proto') {
                    $protocol = $partParts[1];
                    $protocol = trim($protocol, '"\'');
                    return strtolower($protocol);
                }
            }
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $protoParts = explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO']);
            return strtolower(trim($protoParts[0]));
        }
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            return strtolower($_SERVER['REQUEST_SCHEME']);
        }
        return 'http';
    }

    /**
     * @return string
     */
    protected static function getProtocol(): string
    {
        $protocol = static::readProtocol();
        if ($protocol === 'https') {
            return 'https';
        }
        return 'http';
    }

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
        return static::$base = new Uri(static::getProtocol() . "://" . $host);
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
     * @return bool
     */
    public static function isApi(): bool
    {
        $currentHost = static::getCurrent()->getHost();
        $apiHost = static::getApi()->getHost();
        return $currentHost === $apiHost;
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
<?php

namespace Aternos\Mclogs\Router;

enum Method: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';

    public static function getCurrent(): self
    {
        return self::tryFrom($_SERVER['REQUEST_METHOD']) ?? self::GET;
    }
}
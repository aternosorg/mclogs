<?php

namespace Cache;

class RedisCache implements CacheInterface
{
    /**
     * Storage array.
     * Format: key => [value, expiresAt|null]
     *
     * @var array<string, array{0: string, 1: ?int}>
     */
    private static array $store = [];

    /**
     * @inheritDoc
     */
    public static function Set(string $key, string $value, ?int $duration = null): void
    {
        $expiresAt = $duration ? time() + $duration : null;
        self::$store[$key] = [$value, $expiresAt];
    }

    /**
     * @inheritDoc
     */
    public static function Get(string $key): ?string
    {
        if (!isset(self::$store[$key])) {
            return null;
        }

        [$value, $expiresAt] = self::$store[$key];

        // Expired?
        if ($expiresAt !== null && $expiresAt < time()) {
            unset(self::$store[$key]);
            return null;
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public static function Exists(string $key): bool
    {
        if (!isset(self::$store[$key])) {
            return false;
        }

        [, $expiresAt] = self::$store[$key];

        if ($expiresAt !== null && $expiresAt < time()) {
            unset(self::$store[$key]);
            return false;
        }

        return true;
    }
}
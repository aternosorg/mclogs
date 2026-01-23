<?php

namespace Aternos\Mclogs\Config;

enum ConfigKey
{
    case STORAGE_LOGS_BACKEND;
    case STORAGE_LOGS_TTL;
    case STORAGE_CACHE_BACKEND;
    case STORAGE_CACHE_TTL;
    case STORAGE_TOKEN_BACKEND;
    case STORAGE_TOKEN_TTL;

    case REDIS_HOST;
    case REDIS_PORT;

    case MONGODB_URL;
    case MONGODB_DATABASE;

    case FILESYSTEM_PATH;

    case ID_LENGTH;

    case LIMIT_BYTES;
    case LIMIT_LINES;

    case LEGAL_ABUSE;
    case LEGAL_IMPRINT;
    case LEGAL_PRIVACY;

    /**
     * Get the default value for the config key
     *
     * @return string|int|null
     */
    public function getDefaultValue(): string|int|null
    {
        return match ($this) {
            ConfigKey::STORAGE_LOGS_BACKEND,
            ConfigKey::STORAGE_CACHE_BACKEND,
            ConfigKey::STORAGE_TOKEN_BACKEND => 'mongodb',
            ConfigKey::STORAGE_LOGS_TTL => 90 * 24 * 60 * 60,
            ConfigKey::STORAGE_CACHE_TTL => 24 * 60 * 60,
            ConfigKey::STORAGE_TOKEN_TTL => 7 * 24 * 60 * 60,

            ConfigKey::REDIS_HOST => 'redis',
            ConfigKey::REDIS_PORT => 6379,

            ConfigKey::MONGODB_URL => 'mongodb://mongo:27017',
            ConfigKey::MONGODB_DATABASE => 'mclogs',

            ConfigKey::FILESYSTEM_PATH => '/storage/logs',

            ConfigKey::ID_LENGTH => 6,

            ConfigKey::LIMIT_BYTES => 10 * 1024 * 1024,
            ConfigKey::LIMIT_LINES => 25000,

            ConfigKey::LEGAL_ABUSE => 'abuse@aternos.org',
            ConfigKey::LEGAL_IMPRINT => 'https://aternos.gmbh/en/imprint',
            ConfigKey::LEGAL_PRIVACY => 'https://aternos.gmbh/en/mclogs/privacy',

            default => null
        };
    }

    /**
     * Get environment variable name
     *
     * @return string
     */
    public function getEnvironmentVariable(): string
    {
        return "MCLOGS_" . $this->name;
    }

    /**
     * @return array
     */
    public function getJSONPath(): array
    {
        $parts = explode("_", $this->name);
        return array_map(fn($part) => strtolower($part), $parts);
    }
}

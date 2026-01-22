<?php

namespace Aternos\Mclogs\Config;

enum ConfigKey
{
    case STORAGE_TYPE;
    case STORAGE_TIME;
    case STORAGE_LIMIT_BYTES;
    case STORAGE_LIMIT_LINES;

    case REDIS_HOST;
    case REDIS_PORT;

    case MONGODB_URL;
    case MONGODB_DATABASE;

    case FILESYSTEM_PATH;

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
            ConfigKey::STORAGE_TYPE => 'mongodb',
            ConfigKey::STORAGE_TIME => 90 * 24 * 60 * 60,
            ConfigKey::STORAGE_LIMIT_BYTES => 10 * 1024 * 1024,
            ConfigKey::STORAGE_LIMIT_LINES => 25000,

            ConfigKey::REDIS_HOST => 'redis',
            ConfigKey::REDIS_PORT => 6379,

            ConfigKey::MONGODB_URL => 'mongodb://mongo:27017',
            ConfigKey::MONGODB_DATABASE => 'mclogs',

            ConfigKey::FILESYSTEM_PATH => '/storage/logs',

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

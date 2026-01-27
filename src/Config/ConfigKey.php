<?php

namespace Aternos\Mclogs\Config;

enum ConfigKey
{
    case STORAGE_TTL;
    case STORAGE_LIMIT_BYTES;
    case STORAGE_LIMIT_LINES;

    case MONGODB_URL;
    case MONGODB_DATABASE;

    case ID_LENGTH;

    case LEGAL_ABUSE;
    case LEGAL_IMPRINT;
    case LEGAL_PRIVACY;

    case FRONTEND_NAME;
    case FRONTEND_ANALYTICS;
    case FRONTEND_COLOR_BACKGROUND;
    case FRONTEND_COLOR_TEXT;
    case FRONTEND_COLOR_ACCENT;
    case FRONTEND_COLOR_ERROR;

    case WORKER_REQUESTS;

    /**
     * Get the default value for the config key
     *
     * @return string|int|null
     */
    public function getDefaultValue(): string|int|null
    {
        return match ($this) {
            ConfigKey::STORAGE_TTL => 90 * 24 * 60 * 60,
            ConfigKey::STORAGE_LIMIT_BYTES => 10 * 1024 * 1024,
            ConfigKey::STORAGE_LIMIT_LINES => 25000,

            ConfigKey::MONGODB_URL => 'mongodb://mongo:27017',
            ConfigKey::MONGODB_DATABASE => 'mclogs',

            ConfigKey::ID_LENGTH => 7,

            ConfigKey::LEGAL_ABUSE,
            ConfigKey::LEGAL_PRIVACY,
            ConfigKey::LEGAL_IMPRINT,
            ConfigKey::FRONTEND_ANALYTICS => false,

            ConfigKey::FRONTEND_COLOR_BACKGROUND => "#1a1a1a",
            ConfigKey::FRONTEND_COLOR_TEXT => "#e8e8e8",
            ConfigKey::FRONTEND_COLOR_ACCENT => "#5cb85c",
            ConfigKey::FRONTEND_COLOR_ERROR => "#f62451",

            ConfigKey::WORKER_REQUESTS => 500,

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

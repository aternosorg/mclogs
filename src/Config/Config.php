<?php

namespace Aternos\Mclogs\Config;

use Aternos\Mclogs\Util\Singleton;
use Aternos\Mclogs\Util\URL;

class Config
{
    use Singleton;

    protected array $jsonData = [];

    protected function __construct()
    {
        $configPath = __DIR__ . "/../../config.json";
        if (file_exists($configPath)) {
            $jsonContent = file_get_contents($configPath);
            $data = @json_decode($jsonContent, true);
            if (is_array($data)) {
                $this->jsonData = $data;
            }
        }
    }

    /**
     * Get config value by checking environment variable, then config file, then default value
     *
     * @param ConfigKey $key
     * @return mixed
     */
    public function get(ConfigKey $key): mixed
    {
        $env = getenv($key->getEnvironmentVariable());
        if ($env !== false) {
            return $env;
        }

        $json = $this->getJsonValue($key->getJSONPath());
        if ($json !== null) {
            return $json;
        }

        return $key->getDefaultValue();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->get(ConfigKey::FRONTEND_NAME) ?? URL::getBase()->getHost();
    }

    /**
     * Recursively get a value from the json data by path
     *
     * @param array $path
     * @param array|null $data
     * @return mixed
     */
    protected function getJsonValue(array $path, ?array $data = null): mixed
    {
        if ($data === null) {
            $data = $this->jsonData;
        }

        $nextKey = array_shift($path);

        if (!isset($data[$nextKey])) {
            return null;
        }

        $nextData = $data[$nextKey];
        if (count($path) === 0) {
            return $nextData;
        }
        if (!is_array($nextData)) {
            return null;
        }
        return $this->getJsonValue($path, $nextData);
    }
}
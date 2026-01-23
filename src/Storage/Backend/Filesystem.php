<?php

namespace Aternos\Mclogs\Storage\Backend;

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use RuntimeException;

class Filesystem extends StorageBackend
{
    protected ?string $basePath = null;

    /**
     * @return string
     */
    protected function getBasePath(): string
    {
        if ($this->basePath) {
            return $this->basePath;
        }

        $rootPath = Config::getInstance()->get(ConfigKey::FILESYSTEM_PATH);
        $rootPath = rtrim($rootPath, '/') . '/';

        if (!is_writable($rootPath)) {
            throw new RuntimeException("Filesystem storage driver could not write to " . $rootPath . ". Please check if the directory exists and is writable.");
        }

        $basePath = $rootPath . $this->getName() . '/';
        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true);
        }

        if (!is_writable($basePath)) {
            throw new RuntimeException("Filesystem storage driver could not write to " . $basePath . ". Please check if the directory exists and is writable.");
        }

        return $this->basePath = $basePath;
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getPath(string $id): string
    {
        return $this->getBasePath() . $id;
    }

    /**
     * @inheritDoc
     */
    public function put(string $id, string $data, ?int $ttl = null): bool
    {
        file_put_contents($this->getPath($id), $data);
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): ?string
    {
        if (!file_exists($this->getPath($id))) {
            return null;
        }

        return file_get_contents($this->getPath($id)) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function renew(string $id, ?int $ttl = null): bool
    {
        if (!file_exists($this->getPath($id))) {
            return false;
        }

        return touch($this->getPath($id));
    }

    /**
     * @inheritDoc
     */
    public function getId(): StorageBackendId
    {
        return StorageBackendId::FILESYSTEM;
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return file_exists($this->getPath($id));
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id): bool
    {
        if (!file_exists($this->getPath($id))) {
            return true;
        }

        return unlink($this->getPath($id));
    }
}
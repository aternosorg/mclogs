<?php

namespace Aternos\Mclogs\Storage;


use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Id;
use RuntimeException;

class FilesystemStorage extends Storage
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

        $basePath = Config::getInstance()->get(ConfigKey::FILESYSTEM_PATH);

        if (!is_writable($basePath)) {
            throw new RuntimeException("Filesystem storage driver could not write to " . $basePath . ". Please check if the directory exists and is writable.");
        }

        return $this->basePath = $basePath;
    }

    /**
     * @param Id $id
     * @return string
     */
    protected function getPath(Id $id): string
    {
        return $this->getBasePath() . $id->getRaw();
    }

    /**
     * @inheritDoc
     */
    public function put(string $data): ?Id
    {
        $id = new Id(storageId: StorageId::FILESYSTEM);

        do {
            $id->generateNew();
        } while (file_exists($this->getPath($id)));

        file_put_contents($this->getPath($id), $data);
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function get(Id $id): ?string
    {
        if (!file_exists($this->getPath($id))) {
            return null;
        }

        return file_get_contents($this->getPath($id)) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function renew(Id $id): bool
    {
        if (!file_exists($this->getPath($id))) {
            return false;
        }

        return touch($this->getPath($id));
    }
}
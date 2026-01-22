<?php

namespace Aternos\Mclogs\Storage;


class Filesystem implements StorageInterface
{

    /**
     * Put some data in the storage, returns the (new) id for the data
     *
     * @param string $data
     * @return ?\Aternos\Mclogs\Id ID or false
     * @throws \Exception
     */
    public static function Put(string $data): ?\Aternos\Mclogs\Id
    {
        $config = \Aternos\Mclogs\Config::Get("filesystem");
        $basePath = CORE_PATH . $config['path'];

        if (!is_writable($basePath)) {
            throw new \Exception("Filesystem storage driver could not write to " . $basePath . ". Please check if the directory exists and is writable.");
        }

        $id = new \Aternos\Mclogs\Id();
        $id->setStorage("f");

        do {
            $id->regenerate();
        } while (file_exists($basePath . $id->getRaw()));

        file_put_contents($basePath . $id->getRaw(), $data);
        return $id;
    }

    /**
     * Get some data from the storage by id
     *
     * @param \Aternos\Mclogs\Id $id
     * @return ?string Data or null, e.g. if it doesn't exist
     */
    public static function Get(\Aternos\Mclogs\Id $id): ?string
    {
        $config = \Aternos\Mclogs\Config::Get("filesystem");
        $basePath = CORE_PATH . $config['path'];

        if (!file_exists($basePath . $id->getRaw())) {
            return false;
        }

        return file_get_contents($basePath . $id->getRaw()) ?: null;
    }

    /**
     * Renew the data to reset the time to live
     *
     * @param \Aternos\Mclogs\Id $id
     * @return bool Success
     */
    public static function Renew(\Aternos\Mclogs\Id $id): bool
    {
        $config = \Aternos\Mclogs\Config::Get("filesystem");
        $basePath = CORE_PATH . $config['path'];

        if (!file_exists($basePath . $id->getRaw())) {
            return false;
        }

        return touch($basePath . $id->getRaw());
    }
}
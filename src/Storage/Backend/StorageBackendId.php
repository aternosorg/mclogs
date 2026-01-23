<?php

namespace Aternos\Mclogs\Storage\Backend;

enum StorageBackendId: string
{
    case MONGODB = 'm';
    case REDIS = 'r';
    case FILESYSTEM = 'f';

    /**
     * @return class-string<StorageBackendInterface>
     */
    public function getClass(): string
    {
        return match ($this) {
            StorageBackendId::MONGODB => MongoDB::class,
            StorageBackendId::REDIS => Redis::class,
            StorageBackendId::FILESYSTEM => Filesystem::class,
        };
    }
}
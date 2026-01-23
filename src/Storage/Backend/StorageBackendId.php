<?php

namespace Aternos\Mclogs\Storage\Backend;

enum StorageBackendId: string
{
    case MONGODB = 'm';
    case REDIS = 'r';
    case FILESYSTEM = 'f';
}
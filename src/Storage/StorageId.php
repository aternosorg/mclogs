<?php

namespace Aternos\Mclogs\Storage;

enum StorageId: string
{
    case MONGODB = 'm';
    case REDIS = 'r';
    case FILESYSTEM = 'f';
}
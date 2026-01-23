<?php

namespace Aternos\Mclogs\Storage;

enum StorageName: string
{
    case LOGS = 'logs';
    case CACHE = 'cache';
    case TOKEN = 'token';
}
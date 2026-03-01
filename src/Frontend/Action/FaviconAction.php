<?php

namespace IndifferentKetchup\IBLogs\Frontend\Action;

use IndifferentKetchup\IBLogs\Router\Action;

class FaviconAction extends Action
{
    public function run(): bool
    {
        header('Content-Type: image/svg+xml');
        require __DIR__ . "/../../../web/frontend/parts/favicon.php";
        return true;
    }
}
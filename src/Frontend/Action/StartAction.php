<?php

namespace IndifferentKetchup\IBLogs\Frontend\Action;

use IndifferentKetchup\IBLogs\Router\Action;

class StartAction extends Action
{
    public function run(): bool
    {
        require __DIR__ . "/../../../web/frontend/start.php";
        return true;
    }
}
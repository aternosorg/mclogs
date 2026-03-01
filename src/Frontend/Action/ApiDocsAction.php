<?php

namespace IndifferentKetchup\IBLogs\Frontend\Action;

use IndifferentKetchup\IBLogs\Router\Action;

class ApiDocsAction extends Action
{
    public function run(): bool
    {
        require __DIR__ . "/../../../web/frontend/api-docs.php";
        return true;
    }
}
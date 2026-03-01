<?php

namespace IndifferentKetchup\IBLogs\Api\Action;

use IndifferentKetchup\IBLogs\Router\Action;

class EmptyAction extends Action
{
    public function run(): bool
    {
        return true;
    }
}
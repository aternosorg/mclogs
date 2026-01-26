<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Router\Action;

class StartAction extends Action
{
    public function run(): bool
    {
        require_once __DIR__ . "/../../../web/frontend/main.php";
        return true;
    }
}
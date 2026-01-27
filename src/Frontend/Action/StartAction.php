<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Router\Action;

class StartAction extends Action
{
    public function run(): bool
    {
        require __DIR__ . "/../../../web/frontend/start.php";
        return true;
    }
}
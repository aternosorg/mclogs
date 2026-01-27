<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Router\Action;

class FaviconAction extends Action
{
    public function run(): bool
    {
        header('Content-Type: image/svg+xml');
        require __DIR__ . "/../../../web/frontend/parts/favicon.php";
        return true;
    }
}
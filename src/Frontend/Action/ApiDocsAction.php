<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Router\Action;

class ApiDocsAction extends Action
{
    public function run(): bool
    {
        require __DIR__ . "/../../../web/frontend/api-docs.php";
        return true;
    }
}
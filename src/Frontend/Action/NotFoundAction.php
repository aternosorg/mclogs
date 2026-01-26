<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Router\Action;

class NotFoundAction extends Action
{
    public function run(): bool
    {
        http_response_code(404);
        require_once __DIR__ . "/../../../web/frontend/404.php";
        return true;
    }
}
<?php

namespace Aternos\Mclogs\Api\Action;

use Aternos\Mclogs\Router\Action;

class EmptyAction extends Action
{
    public function run(): bool
    {
        return true;
    }
}
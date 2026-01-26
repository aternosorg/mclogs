<?php

use Aternos\Mclogs\Frontend\Action;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Router\Method;
use Aternos\Mclogs\Router\Router;

require_once __DIR__ . '/../../vendor/autoload.php';

new Router()
    ->register(Method::GET, "#^/$#", new Action\StartAction())
    ->register(Method::GET, "#^/" . Id::PATTERN . "$#", new Action\ViewLogAction())
    ->register(Method::POST, "#^/new$#", new Action\CreateLogAction())
    ->register(Method::DELETE, "#^/" . Id::PATTERN . "$#", new Action\DeleteLogAction())
    ->setDefaultAction(new Action\NotFoundAction())
    ->run();

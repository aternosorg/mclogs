<?php

use Aternos\Mclogs\Frontend;
use Aternos\Mclogs\Api\Action;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Router\Method;
use Aternos\Mclogs\Router\Router;

new Router()
    ->register(Method::GET, "#^/$#", new Frontend\Action\ApiDocsAction())
    ->register(Method::OPTIONS, "#^/.*$#", new Action\EmptyAction())
    ->register(Method::POST, "#^/1/log/?$#", new Action\CreateLogAction())
    ->register(Method::GET, "#^/1/log/" . Id::PATTERN . "$#", new Action\LogInfoAction())
    ->register(Method::DELETE, "#^/1/log/" . Id::PATTERN . "$#", new Action\DeleteLogAction())
    ->register(Method::GET, "#^/1/insights/" . Id::PATTERN . "$#", new Action\LogInsightsAction())
    ->register(Method::GET, "#^/1/raw/" . Id::PATTERN . "$#", new Action\RawLogAction())
    ->register(Method::POST, "#^/1/analyse/?$#", new Action\AnalyseLogAction())
    ->register(Method::GET, "#^/1/errors/rate$#", new Action\RateLimitErrorAction())
    ->register(Method::GET, "#^/1/limits$#", new Action\GetLimitsAction())
    ->setDefaultAction(new Action\EndpointNotFoundAction())
    ->run();

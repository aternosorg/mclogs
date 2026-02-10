<?php

namespace Aternos\Mclogs\Api;

use Aternos\Mclogs\Router\Router;
use Aternos\Mclogs\Frontend;
use Aternos\Mclogs\Id;
use Aternos\Mclogs\Router\Method;

class ApiRouter extends Router
{
    protected function __construct()
    {
        parent::__construct();
        $this->register(Method::GET, "#^/$#", new Frontend\Action\ApiDocsAction())
            ->register(Method::OPTIONS, "#^/.*$#", new Action\EmptyAction())
            ->register(Method::POST, "#^/1/log/?$#", new Action\CreateLogAction())
            ->register(Method::GET, "#^/1/log/" . Id::PATTERN . "$#", new Action\LogInfoAction())
            ->register(Method::DELETE, "#^/1/log/" . Id::PATTERN . "$#", new Action\DeleteLogAction())
            ->register(Method::POST, "#^/1/bulk/log/delete/?$#", new Action\BulkDeleteLogsAction())
            ->register(Method::GET, "#^/1/insights/" . Id::PATTERN . "$#", new Action\LogInsightsAction())
            ->register(Method::GET, "#^/1/raw/" . Id::PATTERN . "$#", new Action\RawLogAction())
            ->register(Method::POST, "#^/1/analyse/?$#", new Action\AnalyseLogAction())
            ->register(Method::GET, "#^/1/errors/rate$#", new Action\RateLimitErrorAction())
            ->register(Method::GET, "#^/1/limits$#", new Action\GetLimitsAction())
            ->register(Method::GET, "#^/1/filters#", new Action\GetFiltersAction())
            ->setDefaultAction(new Action\EndpointNotFoundAction());
    }
}

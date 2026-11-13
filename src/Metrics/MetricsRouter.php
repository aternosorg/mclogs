<?php

namespace Aternos\Mclogs\Metrics;

use Aternos\Mclogs\Api\Action\EndpointNotFoundAction;
use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Router\Method;
use Aternos\Mclogs\Router\Router;

/**
 * Routes for requests that arrive on the internal metrics port
 */
class MetricsRouter extends Router
{
    /**
     * Name of the $_SERVER variable that the internal listener in docker/Caddyfile sets
     * to the port a request really arrived on
     *
     * SERVER_PORT cannot be used for this, because it is read from the Host header of the request.
     */
    protected const string LISTENER_PORT_VARIABLE = 'MCLOGS_LISTENER_PORT';

    protected function __construct()
    {
        parent::__construct();
        $this->register(Method::GET, "#^/metrics$#", new Action\MetricsAction())
            ->setDefaultAction(new EndpointNotFoundAction());
    }

    /**
     * Check if the current request arrived on the internal metrics port while metrics are enabled
     *
     * @return bool
     */
    public static function isResponsible(): bool
    {
        $config = Config::getInstance();
        if (!$config->isMetricsEnabled()) {
            return false;
        }

        $listenerPort = $_SERVER[static::LISTENER_PORT_VARIABLE] ?? null;
        if ($listenerPort === null) {
            return false;
        }
        return (string)$listenerPort === (string)$config->get(ConfigKey::METRICS_PORT);
    }
}

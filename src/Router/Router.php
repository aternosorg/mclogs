<?php

namespace Aternos\Mclogs\Router;

use Aternos\Mclogs\Util\Singleton;
use Aternos\Mclogs\Util\URL;

class Router
{
    use Singleton;

    /**
     * @var Route[]
     */
    protected array $routes = [];

    protected ?Action $defaultAction = null;

    /**
     * @param Method $method
     * @param string $pattern
     * @param Action $action
     * @return $this
     */
    public function register(Method $method, string $pattern, Action $action): static
    {
        $this->routes[] = new Route($method, $pattern, $action);
        return $this;
    }

    /**
     * @param Action $defaultAction
     * @return $this
     */
    public function setDefaultAction(Action $defaultAction): static
    {
        $this->defaultAction = $defaultAction;
        return $this;
    }

    /**
     * @return $this
     */
    public function run(): static
    {
        $route = $this->findRoute();
        if (!$route) {
            $this->defaultAction?->run();
            return $this;
        }
        $result = $route->getAction()->run();
        if (!$result) {
            $this->defaultAction?->run();
        }
        return $this;
    }

    /**
     * @return Route|null
     */
    protected function findRoute(): ?Route
    {
        $path = URL::getCurrent()->getPath();
        $method = Method::getCurrent();

        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }
        return null;
    }
}

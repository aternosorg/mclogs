<?php

namespace Aternos\Mclogs\Router;

class Route
{
    public function __construct(
        protected Method $method,
        protected string $pattern,
        protected Action $action
    )
    {
    }

    /**
     * @param Method $method
     * @param string $path
     * @return bool
     */
    public function matches(Method $method, string $path): bool
    {
        if ($this->getMethod() !== $method) {
            return false;
        }
        return preg_match($this->getPattern(), $path) === 1;
    }

    /**
     * @return Method
     */
    public function getMethod(): Method
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return Action
     */
    public function getAction(): Action
    {
        return $this->action;
    }
}
<?php

namespace Aternos\Mclogs\Router;

abstract class Action
{
    abstract public function run(): bool;
}
<?php

namespace IndifferentKetchup\IBLogs\Router;

abstract class Action
{
    abstract public function run(): bool;
}
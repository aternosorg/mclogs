<?php

namespace IndifferentKetchup\IBLogs\Frontend\Action;

use IndifferentKetchup\IBLogs\Id;
use IndifferentKetchup\IBLogs\Log;
use IndifferentKetchup\IBLogs\Router\Action;
use IndifferentKetchup\IBLogs\Util\URL;

class ViewLogAction extends Action
{
    public function run(): bool
    {
        $id = new Id(URL::getLastPathPart());
        $log = Log::find($id);
        if (!$log) {
            return false;
        }

        $log->renew();

        require __DIR__ . "/../../../web/frontend/log.php";
        return true;
    }
}
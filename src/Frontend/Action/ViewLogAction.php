<?php

namespace Aternos\Mclogs\Frontend\Action;

use Aternos\Mclogs\Id;
use Aternos\Mclogs\Log;
use Aternos\Mclogs\Router\Action;
use Aternos\Mclogs\Util\URL;

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
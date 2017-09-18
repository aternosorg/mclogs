<?php

$id = new Id(substr($_SERVER['REQUEST_URI'], 1));
$log = new Log($id);

if (!$log->exists()) {
    echo "404 und so";
    exit;
}
echo $log->get();
$log->renew();
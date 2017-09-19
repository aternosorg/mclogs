<style>
    * {
        font-family: monospace;
    }

    .level-prefix {
        font-weight: bold;
    }

    .level-info {
        color: black;
    }
    .level-info .level-prefix {
        color: dodgerblue;
    }
    .level-warn {
        color: orangered;
    }
    .level-error {
        color: red;
    }

</style>
<?php

$id = new Id(substr($_SERVER['REQUEST_URI'], 1));
$log = new Log($id);

if (!$log->exists()) {
    echo "404 und so";
    exit;
}
echo $log->get();
$log->renew();
<style>
    * {
        font-family: monospace;
    }

    body {
        background: #2d3943;
        color: #fff;
    }

    .level-prefix {
        font-weight: bold;
    }

    .level-info {
        color: white;
    }
    .level-info .level-prefix {
        color: dodgerblue;
    }
    .level-warn,
    .level-warning {
        color: orange;
    }
    .level-error,
    .level-severe {
        color: red;
    }

    .format-black {
        color: #000;
    }

    .format-darkblue {
        color: #0000AA;
    }

    .format-darkgreen {
        color: #00AA00;
    }

    .format-darkaqua {
        color: #00AAAA;
    }

    .format-darkred {
        color: #AA0000;
    }

    .format-darkpurple {
        color: #AA00AA;
    }

    .format-gold {
        color: #FFAA00;
    }

    .format-gray {
        color: #AAAAAA;
    }

    .format-darkgray {
        color: #555555;
    }

    .format-blue {
        color: #5555FF;
    }

    .format-green {
        color: #55FF55;
    }

    .format-aqua {
        color: #55FFFF;
    }

    .format-red {
        color: #FF5555;
    }

    .format-lightpurple {
        color: #FF55FF;
    }

    .format-yellow {
        color: #FFFF55;
    }

    .format-white {
        color: #FFFFFF;
    }

    .format-reset {
        color: #FFFFFF;
        font-weight: normal;
        text-decoration: none;
        font-style: normal;
        display: inline-block;
    }

    .format-bold {
        font-weight: bold;
    }

    .format-underline {
        text-decoration: underline;
    }

    .format-italic {
        font-style: italic;
    }

    .format-strike {
        text-decoration: line-through;
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
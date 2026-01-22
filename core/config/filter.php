<?php

$config = [

    /**
     * Filters applied before saving the log
     *
     * The classes should implement \Filter\Pre\PreFilterInterface
     */
    'pre' => [
        '\\Aternos\\Mclogs\\Filter\\Pre\\Trim',
        '\\Aternos\\Mclogs\\Filter\\Pre\\Length',
        '\\Aternos\\Mclogs\\Filter\\Pre\\Lines',
        '\\Aternos\\Mclogs\\Filter\\Pre\\Ip',
        '\\Aternos\\Mclogs\\Filter\\Pre\\Username',
        '\\Aternos\\Mclogs\\Filter\\Pre\\AccessToken'
    ],
];
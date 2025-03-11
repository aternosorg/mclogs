<?php

$config = [

    /**
     * Filters applied before saving the log
     *
     * The classes should implement \Filter\Pre\PreFilterInterface
     */
    'pre' => [
        '\\Filter\\Pre\\Trim',
        '\\Filter\\Pre\\Lines',
        '\\Filter\\Pre\\Length',
        '\\Filter\\Pre\\Ip',
        '\\Filter\\Pre\\Username',
        '\\Filter\\Pre\\AccessToken'
    ],
];
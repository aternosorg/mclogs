<?php

$config = [

    /**
     * Filters applied before saving the log
     *
     * The classes should implement \Filter\Pre\PreFilterInterface
     */
    'pre' => [
        '\\Aternos\\Mclogs\\Filter\\TrimFilter',
        '\\Aternos\\Mclogs\\Filter\\LengthFilter',
        '\\Aternos\\Mclogs\\Filter\\LinesFilter',
        '\\Aternos\\Mclogs\\Filter\\IpFilter',
        '\\Aternos\\Mclogs\\Filter\\UsernameFilter',
        '\\Aternos\\Mclogs\\Filter\\AccessTokenFilter'
    ],
];
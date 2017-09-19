<?php

$config = [

    /**
     * Filters applied before saving the log
     *
     * The classes should implement \Filter\Pre\PreFilterInterface
     */
    'pre' => [
        '\\Filter\\Pre\\Ip'
    ],

    /**
     * Filters applied before showing log to user
     *
     * The classes should implement \Filter\Post\PostFilterInterface
     */
    'post' => [
        '\\Filter\\Post\\HtmlEntities',
        '\\Filter\\Post\\LineBreaks'
    ]

];
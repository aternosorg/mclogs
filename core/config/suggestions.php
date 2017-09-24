<?php

$config = [
    [
        "id" => 'suggestion-plugin-major-error-%s',
        "pattern" => '/Could not load \'plugins\/([^\.]+)\.jar\' in folder \'[^\']+\'/',
        "answer" => 'Remove or use another version of the plugin %s, because it throws major errors.',
    ],
    [
        "id" => 'suggestion-plugin-major-error-%s',
        "pattern" => '/Error occurred while enabling (\w+) [^\(]+\(Is it up to date\?\)/',
        "answer" => 'Remove or use another version of the plugin %s, because it throws major errors.',
    ],
    [
        "id" => 'suggestion-plugin-minor-error-%s',
        "pattern" => '/Could not pass event \w+ to (\w+) .*/',
        "answer" => 'Use another version of the plugin %s, because it throws minor errors.',
    ],
    [
        "id" => 'suggestion-plugin-dependency-%1$s-%2$s',
        "pattern" => '/Could not load \'plugins\/((?!\.jar).*)\.jar\' in folder \'[^\']+\'\norg\.bukkit\.plugin\.UnknownDependencyException\: (\w+)/',
        "answer" => 'Install the plugin %2$s, because it is required by the plugin %1$s.',
        "remove" => [
            'suggestion-plugin-major-error-%1$s',
            'suggestion-plugin-minor-error-%1$s'
        ]
    ]
];
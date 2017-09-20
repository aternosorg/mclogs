<?php

namespace Filter\Post;

class HighlightPluginErrors implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Find plugin errors and highlight them with a span
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $patterns = [
            "plugin-error plugin-not-load" => '/(Could not load \'[^\']+\' in folder \'[^\']+\')/',
            "plugin-error plugin-enabling" => '/(Error occurred while enabling [^\(]+\(Is it up to date\?\))/',
            "plugin-error plugin-event-error" => '/(Could not pass event \w+ to .*)/'
        ];

        foreach ($patterns as $class => $pattern) {
            $data = preg_replace(
                $pattern,
                '<span class="'.$class.'">$1</span>',
                $data
            );
        }

        return $data;
    }
}
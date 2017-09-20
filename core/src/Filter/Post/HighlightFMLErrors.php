<?php

namespace Filter\Post;

class HighlightFMLErrors implements PostFilterInterface
{

    /**
     * Filter the $data string, add some $meta data and return the string
     *
     * Find fml errors and highlight them with a span
     *
     * @param string $data
     * @param array $meta
     * @return string
     */
    public static function Filter(string $data, array &$meta): string
    {
        $patterns = [
            "highlight-warning fml-warning fml-confirm" => '/(Run the command \/fml confirm or or \/fml cancel to proceed.)/',
            "highlight-warning fml-warning fml-missing" => '/(Forge Mod Loader detected missing blocks\/items.)/',
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
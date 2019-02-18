<?php

namespace Printer;

use Aternos\Codex\Printer\Modification;

/**
 * Class ColorModification
 *
 * @author Matthias Neid
 * @package Printer
 */
class ColorModification extends Modification
{
    /**
     * Modify the given string and return it
     *
     * @param string $text
     * @return string
     */
    public function modify(string $text): string
    {
        $colors = \Config::Get('colors');

        $search = array();
        $replace = array();
        foreach ($colors as $code => $color) {
            $search[] = "\e[" . $code;
            $replace[] = '<span class="format format-' . $color . '">';
        }

        $count = 0;
        $text = str_replace($search, $replace, $text, $count);

        for ($j = 0; $j < $count; $j++) {
            $text .= '</span>';
        }

        return $text;
    }
}
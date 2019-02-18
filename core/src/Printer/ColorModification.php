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
     * @var array
     */
    protected $colors;

    public function __construct()
    {
        $this->colors = \Config::Get('colors');
    }

    /**
     * Modify the given string and return it
     *
     * @param string $text
     * @return string
     */
    public function modify(string $text): string
    {
        $search = array();
        $replace = array();
        foreach ($this->colors as $code => $color) {
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
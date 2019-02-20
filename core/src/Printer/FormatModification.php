<?php

namespace Printer;

/**
 * Class FormatModification
 *
 * @package Printer
 */
class FormatModification extends \Aternos\Codex\Minecraft\Printer\FormatModification
{
    /**
     * @param string $format
     * @return string
     */
    protected function getClasses(string $format): string
    {
        return "format format-" . $format;
    }
}
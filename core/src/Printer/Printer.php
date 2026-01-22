<?php

namespace Printer;

use Aternos\Codex\Log\Entry;
use Aternos\Codex\Log\EntryInterface;
use Aternos\Codex\Log\Level;
use Aternos\Codex\Log\LineInterface;
use Aternos\Codex\Printer\ModifiableDefaultPrinter;

/**
 * Class Printer
 *
 * @package Printer
 */
class Printer extends ModifiableDefaultPrinter
{
    public function __construct()
    {
        $this->addModification(new FormatModification());
    }

    /**
     * @var \Id
     */
    protected \Id $id;

    /**
     * @param \Id $id
     * @return Printer
     */
    public function setId(\Id $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    protected function printLog(): string
    {
        return '<table>' . parent::printLog() . '</table>';
    }

    /**
     * @param EntryInterface|null $entry
     * @return string
     * @throws \Exception
     */
    protected function printEntry(?EntryInterface $entry = null): string
    {
        $entry = $entry ?? $this->entry;
        /** @var Entry $entry */
        $return = '';
        $first = true;
        foreach ($entry as $line) {
            $trClass = "entry-no-error";
            if ($entry->getLevel()->asInt() <= Level::ERROR->asInt()) {
                $trClass = "entry-error";
            }
            $return .= '<tr class="entry ' . $trClass . '">';
            $return .= '<td class="line-number-container"><a href="/' . $this->id->get() . '#L' . $line->getNumber() . '" id="L' . $line->getNumber() . '" class="line-number">' . $line->getNumber() . '</a></td>';
            $return .= '<td><span class="level level-' . $entry->getLevel()->asString() . ((!$first) ? " multiline" : "") . '">';
            $lineString = $this->printLine($line);
            if ($entry->getPrefix() !== null) {
                $prefix = htmlentities($entry->getPrefix());
                $lineString = str_replace($prefix, '<span class="level-prefix">' . $prefix . '</span>', $lineString);
            }
            $return .= $lineString;
            $return .= '</span></td>';
            $return .= '</tr>';
            $first = false;
        }

        return $return;
    }

    /**
     * @param LineInterface $line
     * @return string
     */
    protected function printLine(LineInterface $line): string
    {
        return $this->runModifications(htmlentities($line->getText())) . PHP_EOL;
    }
}

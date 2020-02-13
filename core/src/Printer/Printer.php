<?php

namespace Printer;

use Aternos\Codex\Log\Entry;
use Aternos\Codex\Log\EntryInterface;
use Aternos\Codex\Log\LineInterface;
use Aternos\Codex\Log\LogInterface;
use Aternos\Codex\Printer\ModifiableDefaultPrinter;

/**
 * Class Printer
 *
 * @package Printer
 */
class Printer extends ModifiableDefaultPrinter
{
    protected $modifications = [];

    public function __construct()
    {
        $this->addModification(new FormatModification());
    }

    /**
     * @var \Id
     */
    protected $id;

    /**
     * @param \Id $id
     * @return Printer
     */
    public function setId(\Id $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param LogInterface $log
     * @return string
     */
    protected function printLog(LogInterface $log)
    {
        return '<table>' . parent::printLog($log) . '</table>';
    }

    /**
     * @param EntryInterface $entry
     * @return string
     * @throws \Exception
     */
    protected function printEntry(EntryInterface $entry)
    {
        /** @var Entry $entry */
        $return = '';
        $first = true;
        foreach ($entry as $line) {
            $trClass = "entry-no-error";
            if (in_array($entry->getLevel(), \Log::$errorLogLevels)) {
                $trClass = "entry-error";
            }
            $return .= '<tr class="' . $trClass . '">';
            $return .= '<td class="line-number-container"><a href="/' . $this->id->get() . '#L' . $line->getNumber() . '" id="L' . $line->getNumber() . '" class="line-number">' . $line->getNumber() . '</a></td>';
            $return .= '<td><span class="level level-' . $entry->getLevel() . ((!$first) ? " multiline" : "") . '">';
            $lineString = $this->printLine($line);
            if ($entry instanceof \Aternos\Codex\Minecraft\Log\Entry) {
                $lineString = str_replace($entry->getPrefix(), '<span class="level-prefix">' . $entry->getPrefix() . '</span>', $lineString);
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
    protected function printLine(LineInterface $line)
    {
        return $this->runModifications(htmlentities($line->getText())) . PHP_EOL;
    }
}
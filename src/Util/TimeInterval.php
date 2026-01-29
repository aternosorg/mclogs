<?php

namespace Aternos\Mclogs\Util;

class TimeInterval
{
    use Singleton;

    protected const array UNITS = [
        "year"   => 365 * 24 * 60 * 60,
        "month"  => 30 * 24 * 60 * 60,
        "week"   => 7 * 24 * 60 * 60,
        "day"    => 24 * 60 * 60,
        "hour"   => 60 * 60,
        "minute" => 60,
        "second" => 1,
    ];

    /**
     * @param int $value
     * @param string $unit
     * @return string
     */
    protected function formatUnit(int $value, string $unit): string
    {
        if ($value === 1) {
            return $value . " " . $unit;
        } else {
            return $value . " " . $unit . "s";
        }
    }

    /**
     * @param int $duration
     * @param string $separator
     * @return string
     */
    public function format(int $duration, string $separator = ", "): string
    {
        $parts = [];
        while ($duration > 0) {
            foreach (self::UNITS as $unit => $seconds) {
                if ($duration >= $seconds) {
                    $value = intdiv($duration, $seconds);
                    $duration -= $value * $seconds;
                    $parts[] = $this->formatUnit($value, $unit);
                    break;
                }
            }
        }
        return implode($separator, $parts);
    }
}

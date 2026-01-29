<?php

namespace Aternos\Mclogs\Frontend\Settings;

enum Setting: string
{
    case FULL_WIDTH = "fullWidth";
    case NO_WRAP = "noWrap";

    /**
     * @return string
     */
    function getLabel(): string
    {
        return match ($this) {
            Setting::FULL_WIDTH => "Full Width",
            Setting::NO_WRAP => "No Wrap"
        };
    }

    /**
     * @return string|null
     */
    function getBodyClass(): ?string
    {
        return match ($this) {
            Setting::FULL_WIDTH => "setting-full-width",
            Setting::NO_WRAP => "setting-no-wrap",
            default => null
        };
    }
}

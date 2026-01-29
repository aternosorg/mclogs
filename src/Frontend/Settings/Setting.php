<?php

namespace Aternos\Mclogs\Frontend\Settings;

enum Setting: string
{
    case FULL_WIDTH = "fullWidth";
    case NO_WRAP = "noWrap";
    case FLOATING_SCROLLBAR = "floatingScrollbar";


    /**
     * @return string
     */
    function getLabel(): string
    {
        return match ($this) {
            Setting::FULL_WIDTH => "Full Width",
            Setting::NO_WRAP => "No Wrap",
            Setting::FLOATING_SCROLLBAR => "Floating Scrollbar"
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
            Setting::FLOATING_SCROLLBAR => "setting-floating-scrollbar",
            default => null
        };
    }
}

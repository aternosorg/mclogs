<?php

use Aternos\Mclogs\Util\URL;

?>
<header>
    <a href="/" class="logo" >
        <svg class="logo-icon" width="41" height="42" viewBox="0 0 41 42" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="41" height="5" rx="2" fill="currentColor"/>
            <rect y="9.25" width="33" height="5" rx="2" fill="currentColor"/>
            <rect y="18.5" width="19" height="5" rx="2" fill="currentColor"/>
            <rect y="27.75" width="33" height="5" rx="2" fill="currentColor"/>
            <rect y="37" width="41" height="5" rx="2" fill="currentColor"/>
        </svg>
        <span class="logo-text"><?= URL::getBase()->getHost(); ?></span>
    </a>
    <div class="tagline">
        <div class="tagline-main"><span class="title-verb">Share</span> your logs.</div>
        <div class="tagline-sub">Built for Minecraft & Hytale</div>
    </div>
</header>
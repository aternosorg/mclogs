<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;

?>
    <meta charset="utf-8"/>

    <base href="/"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="vendor/fontawesome/css/fontawesome.min.css"/>
    <link rel="stylesheet" href="css/btn.css"/>
    <link rel="stylesheet" href="css/mclogs.css?v=260126b"/>
    <style>
        :root {
            --bg: <?= Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_BACKGROUND); ?>;
            --text: <?= Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_TEXT); ?>;
            --accent: <?= Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_ACCENT); ?>;
            --error: <?= Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_ERROR); ?>;
        }
    </style>

    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" sizes="any"/>
    <link rel="shortcut icon" href="/favicon.svg" type="image/svg+xml">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php if (Config::getInstance()->get(ConfigKey::FRONTEND_ANALYTICS)): ?>
    <script>
        let _paq = window._paq = window._paq || [];
        _paq.push(['disableCookies']);
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            _paq.push(['setTrackerUrl', '/data']);
            _paq.push(['setSiteId', '5']);
            let d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.async = true;
            g.src = '/data.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
<?php endif; ?>
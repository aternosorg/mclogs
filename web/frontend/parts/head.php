<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\URL;

?>
    <meta charset="utf-8"/>

    <base href="/"/>
    <link rel="stylesheet" href="vendor/fontawesome/css/fontawesome.min.css"/>
    <link rel="stylesheet" href="css/mclogs.css"/>
    <style>
        :root {
            --bg: <?= htmlspecialchars(Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_BACKGROUND)); ?>;
            --text: <?= htmlspecialchars(Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_TEXT)); ?>;
            --accent: <?= htmlspecialchars(Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_ACCENT)); ?>;
            --error: <?= htmlspecialchars(Config::getInstance()->get(ConfigKey::FRONTEND_COLOR_ERROR)); ?>;
        }
    </style>

    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" sizes="any"/>
    <link rel="shortcut icon" href="<?= htmlspecialchars(URL::getBase()->withPath("/favicon.svg")->toString()); ?>" type="image/svg+xml">

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

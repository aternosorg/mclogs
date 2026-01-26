<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\URL;

$config = Config::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <title><?= URL::getBase()->getHost(); ?> - Paste, share & analyse your Minecraft logs</title>

        <base href="/" />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css" />
        <link rel="stylesheet" href="css/btn.css" />
        <link rel="stylesheet" href="css/mclogs.css?v=260126b" />

        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

        <meta name="description" content="Easily paste your Minecraft logs to share and analyse them.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <script>
            let _paq = window._paq = window._paq || [];
            _paq.push(['disableCookies']);
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                _paq.push(['setTrackerUrl', '/data']);
                _paq.push(['setSiteId', '5']);
                let d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.async=true; g.src='/data.js'; s.parentNode.insertBefore(g,s);
            })();
        </script>
    </head>
    <body>
        <div class="container">
            <header>
                <a href="/" class="logo" >
                    <img src="img/logo-icon.svg" alt="" />
                    <span class="logo-text">mclo.gs</span>
                </a>
                <div class="tagline"><span class="title-verb">Paste</span> your Minecraft logs.</div>
            </header>

            <main>
                <div class="paste-area" id="dropzone">
                    <div class="paste-placeholder">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Paste or drop your log here</p>
                        <div class="paste-hints">
                            <span><kbd>Ctrl+V</kbd> paste</span>
                            <span><i class="fa-solid fa-file-arrow-up"></i> drop</span>
                            <button type="button" class="link-btn" id="paste-select-file"><i class="fa-solid fa-folder-open"></i> browse</button>
                        </div>
                    </div>
                    <textarea id="paste" data-max-length="10000000" data-max-lines="25000"></textarea>
                    <button type="button" class="btn-save paste-save" disabled><i class="fa-solid fa-share"></i> Share</button>
                </div>
            </main>
            <script>
                const paste = document.getElementById('paste');
                const btn = document.querySelector('.btn-save');
                paste.addEventListener('input', () => { btn.disabled = !paste.value; });
            </script>

            <footer>
                <nav class="footer-nav">
                    <a href="https://github.com/aternosorg/mclogs" target="_blank"><i class="fa-brands fa-github"></i> GitHub</a>
                    <a href="https://modrinth.com/mod/mclogs" target="_blank"><i class="fa-solid fa-cube"></i> Mod</a>
                    <a href="https://modrinth.com/plugin/mclogs" target="_blank"><i class="fa-solid fa-plug"></i> Plugin</a>
                    <a href="/api"><i class="fa-solid fa-code"></i> API</a>
                </nav>
                <span class="footer-text">&copy; 2017-<?=date("Y"); ?> by mclo.gs - a service by <a href="https://aternos.org" target="_blank">Aternos</a></span>
            </footer>
        </div>
        <script src="js/mclogs.js"></script>
    </body>
</html>

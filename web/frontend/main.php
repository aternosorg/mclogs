<?php
$urls = Config::Get('urls');
$legal = Config::Get('legal');
$storage = \Config::Get('storage');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="theme-color" content="#2d3943" />

        <title>mclo.gs - Paste, share & analyse your Minecraft logs</title>

        <base href="/" />

        <link rel="stylesheet" href="vendor/fonts/fonts.css" />
        <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css" />
        <link rel="stylesheet" href="css/btn.css" />
        <link rel="stylesheet" href="css/mclogs.css?v=071224" />

        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

        <meta name="description" content="Easily paste your Minecraft logs to share and analyse them.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

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
        <header class="row navigation">
            <div class="row-inner">
                <a href="/" class="logo">
                    <img src="img/logo.png" />
                </a>
                <div class="menu">
                    <a class="menu-item" href="/#info">
                        <i class="fa fa-info-circle"></i> Info
                    </a>
                    <a class="menu-item" href="/#plugin">
                        <i class="fa fa-database"></i> Plugin
                    </a>
                    <a class="menu-item" href="/#mod">
                        <i class="fa fa-puzzle-piece"></i> Mod
                    </a>
                    <a class="menu-item" href="/#api">
                        <i class="fa fa-code"></i> API
                    </a>
                    <a class="menu-social btn btn-black btn-notext btn-large btn-no-margin" href="https://github.com/aternosorg/mclogs" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
        </header>
        <div class="row dark title">
            <div class="row-inner">
                <h1 class="title-container">
                    <span class="title-verb">Paste</span> your Minecraft logs.
                </h1>
            </div>
        </div>
        <div class="row dark paste">
            <div class="row-inner">
                <div class="paste-box">
                    <div class="paste-header">
                        <div class="paste-header-text">
                            Paste your log here or <span class="btn btn-small btn-no-margin" id="paste-select-file"><i class="fa fa-file-import"></i> Select a file</span>
                        </div>
                        <div class="paste-save btn btn-green btn-no-margin">
                            <i class="fa fa-save"></i> Save
                        </div>
                    </div>
                    <div id="dropzone" class="paste-body">
                        <textarea id="paste" autocomplete="off" spellcheck="false" data-max-length="<?=$storage['maxLength']?>" data-max-lines="<?=$storage['maxLines']?>"></textarea>
                    </div>
                    <div class="paste-footer">
                        <div class="paste-save btn btn-green btn-no-margin">
                            <i class="fa fa-save"></i> Save
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row info" id="info">
            <div class="row-inner">
                <div class="info-item info-blue">
                    <div class="info-icon">
                        <i class="fa fa-clone"></i>
                    </div>
                    <div class="info-details">
                        <div class="info-title">
                            Paste
                        </div>
                        <div class="info-text">
                            Easily paste your Minecraft log file here from any source. Critical information e.g. IP addresses are automatically hidden.
                        </div>
                    </div>
                </div>
                <div class="info-item info-green">
                    <div class="info-icon">
                        <i class="fa fa-share-alt"></i>
                    </div>
                    <div class="info-details">
                        <div class="info-title">
                            Share
                        </div>
                        <div class="info-text">
                            Use your personal short URL to share your Minecraft log with others and find solutions together.
                        </div>
                    </div>
                </div>
                <div class="info-item info-red">
                    <div class="info-icon">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="info-details">
                        <div class="info-title">
                            Analyse
                        </div>
                        <div class="info-text">
                            Find problems in your Minecraft log through intelligent syntax highlighting and analysis.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row dark plugin" id="plugin">
            <div class="row-inner">
                <div class="article left">
                    <div class="article-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="article-info">
                        <div class="article-title">
                            Use our plugin.
                        </div>
                        <div class="article-text">
                            With our plugin you can share your Minecraft log directly from your server with one simple command.
                            Use permissions to share the power with other team members and solve problems together. It's even possible
                            to export old server log files, e.g. after a crash. Critical information like IP addresses are automatically
                            hidden to ensure safety and privacy.
                        </div>
                        <div class="article-buttons">
                            <a href="https://modrinth.com/plugin/mclogs" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-download"></i> modrinth.com
                            </a>
                            <a href="https://hangar.papermc.io/Aternos/mclogs" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-download"></i> hangar.papermc.io
                            </a>
                            <a href="https://dev.bukkit.org/projects/mclogs" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-download"></i> dev.bukkit.org
                            </a>
                            <a href="https://www.spigotmc.org/resources/mclo-gs.47502/" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-download"></i> spigotmc.org
                            </a>
                            <a href="https://github.com/aternosorg/mclogs-integration" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fab fa-github"></i> github.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mod" id="mod">
            <div class="row-inner">
                <div class="article right">
                    <div class="article-icon">
                        <i class="fa fa-puzzle-piece"></i>
                    </div>
                    <div class="article-info">
                        <div class="article-title">
                            Use our mod.
                        </div>
                        <div class="article-text">
                            We also have a mod for Forge, Fabric and NeoForge, so you can use it with your favorite mods. It can be installed on
                            your server with the same features <br /> as the plugin or on your client to share the logs from your client.
                        </div>
                        <div class="article-buttons">
                            <a href="https://modrinth.com/mod/mclogs" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-download"></i> modrinth.com
                            </a>
                            <a href="https://www.curseforge.com/minecraft/mc-mods/mclo-gs" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-download"></i> curseforge.com
                            </a>
                            <a href="https://github.com/aternosorg/mclogs-integration" target="_blank" class="btn btn-blue btn-no-margin">
                                <i class="fab fa-github"></i> github.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row dark api" id="api">
            <div class="row-inner">
                <div class="article left">
                    <div class="article-icon">
                        <i class="fa fa-code"></i>
                    </div>
                    <div class="article-info">
                        <div class="article-title">
                            Use our API.
                        </div>
                        <div class="article-text">
                            Integrate <strong>mclo.gs</strong> directly into your server panel, your hosting software or anything else. This platform
                            was built for high performance automation and can easily be integrated into any existing software via our
                            HTTP API.
                        </div>
                        <div class="article-buttons">
                            <a href="<?=$urls['apiBaseUrl']?>" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-book"></i> API Documentation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row footer">
            <div class="row-inner">
                &copy; 2017-<?=date("Y"); ?> by mclo.gs - a service by <a target="_blank" href="https://aternos.org">Aternos</a> |
                <a target="_blank" href="<?=$legal['imprint']?>">Imprint</a> |
                <a target="_blank" href="<?=$legal['privacy']?>">Privacy</a>
            </div>
        </div>
        <script src="js/mclogs.js?v=130222"></script>
    </body>
</html>

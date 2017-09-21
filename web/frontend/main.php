<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="content-language" content="en" />
        <meta name="theme-color" content="#2d3943" />

        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Play:400,700">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:300,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet" />

        <title>mclo.gs - Paste, share & analyse your Minecraft server logs</title>

        <base href="/frontend/" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/btn.css" />
        <link rel="stylesheet" href="css/mclogs.css" />
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

        <meta name="description" content="Easily paste your Minecraft server logs to share and analyse them.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43611107-4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments)};
            gtag('js', new Date());

            gtag('config', 'UA-43611107-4');
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
                    <a class="menu-item" href="/#api">
                        <i class="fa fa-code"></i> API
                    </a>
                    <a class="menu-social btn btn-blue btn-notext btn-large btn-no-margin" href="https://twitter.com/Aternos" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                </div>
            </div>
        </header>
        <div class="row dark title">
            <div class="row-inner">
                <h1 class="title-container">
                    <span class="title-verb">Paste</span> your Minecraft server logs.
                </h1>
            </div>
        </div>
        <div class="row dark paste">
            <div class="row-inner">
                <div class="paste-box">
                    <div class="paste-header">
                        <div class="paste-header-text">
                            Paste your log here.
                        </div>
                        <div class="paste-save btn btn-green btn-no-margin">
                            <i class="fa fa-save"></i> Save
                        </div>
                    </div>
                    <div class="paste-body">
                        <textarea id="paste" autocomplete="off" spellcheck="false"></textarea>
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
                            Easily paste your Minecraft server log file here from any source. Critical information e.g. IP addresses are automatically hidden.
                        </div>
                    </div>
                </div>
                <div class="info-item info-green">
                    <div class="info-icon">
                        <i class="fa fa-share"></i>
                    </div>
                    <div class="info-details">
                        <div class="info-title">
                            Share
                        </div>
                        <div class="info-text">
                            Use your personal short URL to share your Minecraft server log with others and find solutions together.
                        </div>
                    </div>
                </div>
                <div class="info-item info-red">
                    <div class="info-icon">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <div class="info-details">
                        <div class="info-title">
                            Analyse
                        </div>
                        <div class="info-text">
                            Find problems in your Minecraft server log through intelligent syntax highlighting and analysis.
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
                            With our plugin you can share your Minecraft server log directly from your server with one simple command.
                            Use permissions to share the power with other team members and solve problems together. It's even possible
                            to export old server log files, e.g. after a crash. Critical information like IP addresses are automatically
                            hidden to ensure safety and privacy.
                        </div>
                        <div class="article-buttons">
                            <a href="#" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-link"></i> Get our plugin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row api" id="api">
            <div class="row-inner">
                <div class="article right">
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
                            <a href="https://api.mclo.gs" class="btn btn-blue btn-no-margin">
                                <i class="fa fa-book"></i> API Documentation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row dark footer">
            <div class="row-inner">
                &copy; 2017 by mclo.gs - a service by <a href="https://aternos.org">Aternos</a> | <a href="https://aternos.org/impressum/">Imprint</a>
            </div>
        </div>
        <script src="js/mclogs.js"></script>
    </body>
</html>
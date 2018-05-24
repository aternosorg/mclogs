<?php
$id = new Id(substr($_SERVER['REQUEST_URI'], 1));
$log = new Log($id);

if(!$log->exists()) {
    http_response_code(404);
}
?><!DOCTYPE html>
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
        <link rel="stylesheet" href="css/log.css" />
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

        <meta name="description" content="Easily paste your Minecraft server logs to share and analyse them.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43611107-4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments)};
            gtag('js', new Date());

            gtag('config', 'UA-43611107-4', { 'anonymize_ip': true });
        </script>
    </head>
    <body class="log-body">
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
        <div class="row dark log-row">
            <div class="row-inner">
                <?php if($log->exists()): ?>
                <?php $suggestions = $log->getSuggestions(); ?>
                <?php if(count($suggestions) > 0): ?>
                    <div class="suggestions-container">
                        <div class="show-suggestions btn btn-blue btn-no-margin">
                            <i class="fa fa-info-circle"></i> <span id="suggestion-toggle-text">Show suggestions</span>
                        </div>
                        <div class="suggestions">
                            <?php foreach($suggestions as $suggestionId=>$suggestion): ?>
                                <div class="suggestion">
                                    <div class="suggestion-answer">
                                        <i class="fa fa-info-circle"></i> <?=$suggestion["answer"]; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="log">
                    <?php
                        $log->renew();
                        echo $log->get();
                    ?>
                </div>
                <?php else: ?>
                <div class="not-found">
                    <div class="not-found-title">404 - Log not found.</div>
                    <div class="not-found-text">The log you try to open does not exist (anymore).<br />We automatically delete all logs that weren't opened in the last 72 hours.</div>
                    <div class="not-found-buttons">
                        <a href="/" class="btn btn-no-margin btn-blue btn-small">
                            <i class="fa fa-home"></i> Paste a new log
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if($log->exists()): ?>
        <div class="row row-notice dark">
            <div class="row-inner">
                This log will be saved for 72 hours from their last view.<br />
                <a href="mailto:abuse@aternos.org?subject=Report%20mclo.gs/<?=$id->get(); ?>">Report abuse</a>
            </div>
        </div>
        <?php endif; ?>
        <div class="row footer">
            <div class="row-inner">
                &copy; 2017-<?=date("Y"); ?> by mclo.gs - a service by <a href="https://aternos.org">Aternos</a> | <a href="https://aternos.org/impressum/">Imprint</a>
            </div>
        </div>
        <script src="js/logview.js"></script>
    </body>
</html>

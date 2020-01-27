<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="content-language" content="en" />
        <meta name="theme-color" content="#2d3943" />

        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Play:400,700">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:300,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet" />

        <title>API Documentation - mclo.gs</title>

        <base href="//<?=str_replace("api.", "", $_SERVER['HTTP_HOST']); ?>/frontend/" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/btn.css" />
        <link rel="stylesheet" href="css/mclogs.css" />
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

        <meta name="description" content="Easily paste your Minecraft server logs to share and analyse them.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    </head>
    <body>
        <header class="row navigation">
            <div class="row-inner">
                <a href="/" class="logo">
                    <img src="img/logo.png" />
                </a>
                <div class="menu">
                    <a class="menu-social btn btn-blue btn-notext btn-large btn-no-margin" href="https://twitter.com/Aternos" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                </div>
            </div>
        </header>
        <div class="row docs dark">
            <div class="row-inner">
                <div class="docs-text">
                    <h1 class="docs-title">API Documentation</h1>
                    Integrate <strong>mclo.gs</strong> directly into your server panel, your hosting software or anything else. This platform
                    was built for high performance automation and can easily be integrated into any existing software via our
                    HTTP API.
                </div>
                <div class="docs-icon">
                    <i class="fa fa-code"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row-inner">
                <h2>Endpoint(s)</h2>

                <div class="endpoint">
                    <span class="method">POST</span> <span class="endpoint-url">https://api.mclo.gs/1/log</span> <span class="content-type">application/x-www-form-urlencoded</span>
                </div>
                <table class="endpoint-table">
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td class="endpoint-field">content</td>
                        <td class="endpoint-type">string</td>
                        <td class="endpoint-description">The raw log content as string. Maximum length is around 10MB, will be shortened if necessary.</td>
                    </tr>
                </table>

                <h2>Answers</h2>
                <h3>Success</h3>
                <pre class="answer">
    {
        "success": true,
        "id": "8FlTowW",
        "url": "https://mclo.gs/8FlTowW"
    }</pre>
                <h3>Error</h3>
                <pre class="answer">
    {
        "success": false,
        "error": "Required POST argument 'content' is empty."
    }</pre>
            </div>
        </div>
        <div class="row dark api-notes docs">
            <div class="row-inner">
                <div class="docs-text">
                    <h2>Notes</h2>
                    The API has currently a rate limit of 60 requests per minute per IP address. This is set to ensure the
                    operability of this service. If you have any use case that requires a higher limit, feel free to contact us.
                    This platform is pretty new and is still under heavy development, so if you have any suggestions, how we
                    could improve our service, let us know. :)
                    <div class="notes-buttons">
                        <a class="btn btn-small btn-no-margin btn-blue" href="mailto:matthias@aternos.org">
                            <i class="fa fa-envelope"></i> Contact via mail
                        </a>
                        <a class="btn btn-small btn-no-margin btn-blue" target="_blank" href="https://twitter.com/Aternos">
                            <i class="fa fa-twitter"></i> Contact via Twitter
                        </a>
                    </div>
                </div>
                <div class="docs-icon">
                    <i class="fa fa-sticky-note"></i>
                </div>
            </div>
        </div>
        <div class="row footer">
            <div class="row-inner">
                &copy; 2017-<?=date("Y"); ?> by mclo.gs - a service by <a href="https://aternos.org">Aternos</a> | <a href="https://aternos.org/impressum/">Imprint</a>
            </div>
        </div>
    </body>
</html>
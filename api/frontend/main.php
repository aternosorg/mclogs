<?php
$urls = Config::Get("urls");
$legal = Config::Get('legal');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="theme-color" content="#2d3943" />

        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Play:400,700">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:300,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet" />

        <title>API Documentation - mclo.gs</title>

        <base href="//<?=str_replace("api.", "", $_SERVER['HTTP_HOST']); ?>/" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
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
                    <a class="menu-social btn btn-black btn-notext btn-large btn-no-margin" href="https://github.com/aternosorg/mclogs" target="_blank">
                        <i class="fa fa-github"></i>
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
                <h2>Paste a log file</h2>

                <div class="endpoint">
                    <span class="method">POST</span> <span class="endpoint-url"><?=$urls['apiBaseUrl']?>/1/log</span> <span class="content-type">application/x-www-form-urlencoded</span>
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
                        <td class="endpoint-description">The raw log file content as string. Maximum length is 10MiB and 25k lines, will be shortened if necessary.</td>
                    </tr>
                </table>

                <h3>cURL <span class="command-description">Upload log files from a shell</span></h3>
                <pre class="answer">
curl -X POST --data-urlencode 'content@path/to/latest.log' '<?=$urls['apiBaseUrl']?>/1/log'</pre>
                <h3>Success <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
    "success": true,
    "id": "8FlTowW",
    "url": "<?=$urls['baseUrl']?>/8FlTowW",
    "raw": "<?=$urls['apiBaseUrl']?>/1/raw/8FlTowW"
}</pre>
                <h3>Error <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
    "success": false,
    "error": "Required POST argument 'content' is empty."
}</pre>
            </div>
        </div>
        <div class="row">
            <div class="row-inner">
                <h2>Get the raw log file content</h2>
                <div class="endpoint">
                    <span class="method">GET</span> <span class="endpoint-url"><?=$urls['apiBaseUrl']?>/1/raw/[id]</span>
                </div>
                <table class="endpoint-table">
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td class="endpoint-field">[id]</td>
                        <td class="endpoint-type">string</td>
                        <td class="endpoint-description">The log file id, received from the paste endpoint or from a URL (<?=$urls['baseUrl']?>/[id]).</td>
                    </tr>
                </table>

                <h3>Success <span class="content-type">text/plain</span></h3>
                <pre class="answer">
[18:25:33] [Server thread/INFO]: Starting minecraft server version 1.16.2
[18:25:33] [Server thread/INFO]: Loading properties
[18:25:34] [Server thread/INFO]: Default game type: SURVIVAL
...
</pre>
                <h3>Error <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
    "success": false,
    "error": "Log not found."
}</pre>
            </div>
        </div>
        <div class="row">
            <div class="row-inner">
                <h2>Get insights</h2>

                <div class="endpoint">
                    <span class="method">GET</span> <span class="endpoint-url"><?=$urls['apiBaseUrl']?>/1/insights/[id]</span>
                </div>
                <table class="endpoint-table">
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td class="endpoint-field">[id]</td>
                        <td class="endpoint-type">string</td>
                        <td class="endpoint-description">The log file id, received from the paste endpoint or from a URL (<?=$urls['baseUrl']?>/[id]).</td>
                    </tr>
                </table>

                <h3>Success <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
  "id": "name/type",
  "name": "Software name, e.g. Vanilla",
  "type": "Type name, e.g. Server Log",
  "version": "Version, e.g. 1.12.2",
  "title": "Combined title, e.g. Vanilla 1.12.2 Server Log",
  "analysis": {
    "problems": [
      {
        "message": "A message explaining the problem.",
        "counter": 1,
        "entry": {
          "level": 6,
          "time": null,
          "prefix": "The prefix of this entry, usually the part containing time and loglevel.",
          "lines": [
            {
              "number": 1,
              "content": "The full content of the line."
            }
          ]
        },
        "solutions": [
          {
            "message": "A message explaining a possible solution."
          }
        ]
      }
    ],
    "information": [
      {
        "message": "Label: value",
        "counter": 1,
        "label": "The label of this information, e.g. Minecraft version",
        "value": "The value of this information, e.g. 1.12.2",
        "entry": {
          "level": 6,
          "time": null,
          "prefix": "The prefix of this entry, usually the part containing time and loglevel.",
          "lines": [
            {
              "number": 6,
              "content": "The full content of the line."
            }
          ]
        }
      }
    ]
  }
}</pre>
                <h3>Error <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
    "success": false,
    "error": "Log not found."
}</pre>
            </div>
        </div>
        <div class="row">
            <div class="row-inner">
                <h2>Analyse a log without saving it</h2>

                <div class="endpoint">
                    <span class="method">POST</span> <span class="endpoint-url"><?=$urls['apiBaseUrl']?>/1/analyse</span> <span class="content-type">application/x-www-form-urlencoded</span>
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
                        <td class="endpoint-description">The raw log file content as string. Maximum length is 10MiB and 25k lines, will be shortened if necessary.</td>
                    </tr>
                </table>

                <h3>Success <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
  "id": "name/type",
  "name": "Software name, e.g. Vanilla",
  "type": "Type name, e.g. Server Log",
  "version": "Version, e.g. 1.12.2",
  "title": "Combined title, e.g. Vanilla 1.12.2 Server Log",
  "analysis": {
    "problems": [
      {
        "message": "A message explaining the problem.",
        "counter": 1,
        "entry": {
          "level": 6,
          "time": null,
          "prefix": "The prefix of this entry, usually the part containing time and loglevel.",
          "lines": [
            {
              "number": 1,
              "content": "The full content of the line."
            }
          ]
        },
        "solutions": [
          {
            "message": "A message explaining a possible solution."
          }
        ]
      }
    ],
    "information": [
      {
        "message": "Label: value",
        "counter": 1,
        "label": "The label of this information, e.g. Minecraft version",
        "value": "The value of this information, e.g. 1.12.2",
        "entry": {
          "level": 6,
          "time": null,
          "prefix": "The prefix of this entry, usually the part containing time and loglevel.",
          "lines": [
            {
              "number": 6,
              "content": "The full content of the line."
            }
          ]
        }
      }
    ]
  }
}</pre>
                <h3>Error <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
    "success": false,
    "error": "Required POST argument 'content' is empty."
}</pre>
            </div>
        </div>
        <div class="row">
            <div class="row-inner">
                <h2>Check storage limits</h2>

                <div class="endpoint">
                    <span class="method">GET</span> <span class="endpoint-url"><?=$urls['apiBaseUrl']?>/1/limits</span>
                </div>
                <h3>Success <span class="content-type">application/json</span></h3>
                <pre class="answer">
{
  "storageTime": 7776000,
  "maxLength": 10485760,
  "maxLines": 25000
}</pre>
                <table class="endpoint-table">
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td class="endpoint-field">storageTime</td>
                        <td class="endpoint-type">integer</td>
                        <td class="endpoint-description">The duration in seconds that a log is stored for after the last view.</td>
                    </tr>
                    <tr>
                        <td class="endpoint-field">maxLength</td>
                        <td class="endpoint-type">integer</td>
                        <td class="endpoint-description">Maximum file length in bytes. Logs over this limit will be truncated to this length.</td>
                    </tr>
                    <tr>
                        <td class="endpoint-field">maxLines</td>
                        <td class="endpoint-type">integer</td>
                        <td class="endpoint-description">Maximum number of lines. Additional lines will be removed.</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row dark api-notes docs">
            <div class="row-inner">
                <div class="docs-text">
                    <h2>Notes</h2>
                    The API has currently a rate limit of 60 requests per minute per IP address. This is set to ensure the
                    operability of this service. If you have any use case that requires a higher limit, feel free to contact us.
                    <div class="notes-buttons">
                        <a class="btn btn-small btn-no-margin btn-blue" href="mailto:matthias@aternos.org">
                            <i class="fa fa-envelope"></i> Contact via mail
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
                &copy; 2017-<?=date("Y"); ?> by mclo.gs - a service by <a href="https://aternos.org">Aternos</a> | <a href="<?=$legal['imprint']?>">Imprint</a>
            </div>
        </div>
    </body>
</html>

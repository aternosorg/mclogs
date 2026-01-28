<?php

use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Util\URL;

$config = Config::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title>API Documentation - <?= URL::getBase()->getHost(); ?></title>
        <meta name="description" content="API documentation for mclo.gs - Integrate log sharing directly into your server panel or hosting software." />
    </head>
    <body>
        <div class="container">
            <?php include __DIR__ . '/parts/header.php'; ?>

            <main>
                <div class="api-docs-header">
                    <div class="api-docs-header-content">
                        <h1>API Documentation</h1>
                        <p>Integrate <strong><?= $config->getName(); ?></strong> directly into your server panel, your hosting software or anything else. This platform was built for high performance automation and can easily be integrated into any existing software via our HTTP API.</p>
                    </div>
                </div>
                <div class="api-docs-section">
                    <h2>Paste a log file</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                    <div class="api-endpoint">
                        <span class="api-method">POST</span> <span class="api-url"><?= URL::getApi()->withPath("/1/log")->toString(); ?></span> <span class="content-type">application/x-www-form-urlencoded</span>
                    </div>
                    <div class="api-note">
                        <strong>Note:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.
                    </div>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">content</td>
                            <td class="api-type">string</td>
                            <td class="api-description">The raw log file content as string. Maximum length is 10MiB and 25k lines, will be shortened if necessary.</td>
                        </tr>
                    </table>

                    <h3>cURL <span class="command-description">Upload log files from a shell</span></h3>
                    <pre class="api-code">
curl -X POST --data-urlencode 'content@path/to/latest.log' '<?= URL::getApi()->withPath("/1/log")->toString(); ?>'</pre>
                    <h3>Success <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
{
    "success": true,
    "id": "8FlTowW",
    "url": "<?= URL::getBase()->withPath("/8FlTowW")->toString(); ?>",
    "raw": "<?= URL::getApi()->withPath("/1/raw/8FlTowW")->toString(); ?>"
}</pre>
                    <h3>Error <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
{
    "success": false,
    "error": "Required field 'content' is empty."
}</pre>
                </div>
                <div class="api-docs-section">
                    <h2>Get the raw log file content</h2>
                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= URL::getApi()->toString(); ?>/1/raw/[id]</span>
                    </div>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">[id]</td>
                            <td class="api-type">string</td>
                            <td class="api-description">The log file id, received from the paste endpoint or from a URL (<?= URL::getBase()->toString(); ?>/[id]).</td>
                        </tr>
                    </table>

                    <h3>Success <span class="content-type">text/plain</span></h3>
                    <pre class="api-code">
[18:25:33] [Server thread/INFO]: Starting minecraft server version 1.16.2
[18:25:33] [Server thread/INFO]: Loading properties
[18:25:34] [Server thread/INFO]: Default game type: SURVIVAL
...
</pre>
                    <h3>Error <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
{
    "success": false,
    "error": "Log not found."
}</pre>
                </div>
                <div class="api-docs-section">
                    <h2>Get insights</h2>

                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= URL::getApi()->toString(); ?>/1/insights/[id]</span>
                    </div>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">[id]</td>
                            <td class="api-type">string</td>
                            <td class="api-description">The log file id, received from the paste endpoint or from a URL (<?= URL::getBase()->toString(); ?>/[id]).</td>
                        </tr>
                    </table>

                    <h3>Success <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
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
                    <pre class="api-code">
{
    "success": false,
    "error": "Log not found."
}</pre>
                </div>
                <div class="api-docs-section">
                    <h2>Analyse a log without saving it</h2>

                    <div class="api-endpoint">
                        <span class="api-method">POST</span> <span class="api-url"><?= URL::getApi()->withPath("/1/analyse")->toString(); ?></span> <span class="content-type">application/x-www-form-urlencoded</span>
                    </div>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">content</td>
                            <td class="api-type">string</td>
                            <td class="api-description">The raw log file content as string. Maximum length is 10MiB and 25k lines, will be shortened if necessary.</td>
                        </tr>
                    </table>

                    <h3>Success <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
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
                    <pre class="api-code">
{
    "success": false,
    "error": "Required field 'content' is empty."
}</pre>
                </div>
                <div class="api-docs-section">
                    <h2>Check storage limits</h2>

                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= URL::getApi()->withPath("/1/limits")->toString(); ?></span>
                    </div>
                    <h3>Success <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
{
  "storageTime": 7776000,
  "maxLength": 10485760,
  "maxLines": 25000
}</pre>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">storageTime</td>
                            <td class="api-type">integer</td>
                            <td class="api-description">The duration in seconds that a log is stored for after the last view.</td>
                        </tr>
                        <tr>
                            <td class="api-field">maxLength</td>
                            <td class="api-type">integer</td>
                            <td class="api-description">Maximum file length in bytes. Logs over this limit will be truncated to this length.</td>
                        </tr>
                        <tr>
                            <td class="api-field">maxLines</td>
                            <td class="api-type">integer</td>
                            <td class="api-description">Maximum number of lines. Additional lines will be removed.</td>
                        </tr>
                    </table>
                </div>
                <div class="api-docs-notes">
                    <div class="api-docs-notes-content">
                        <h2>Notes</h2>
                        <p>The API has currently a rate limit of 60 requests per minute per IP address. This is set to ensure the operability of this service. If you have any use case that requires a higher limit, feel free to contact us.</p>
                        <div class="api-docs-notes-actions">
                            <a class="btn btn-small" href="mailto:matthias@aternos.org">
                                <i class="fa-solid fa-envelope"></i> Contact via mail
                            </a>
                        </div>
                    </div>
                </div>
            </main>

            <?php include __DIR__ . '/parts/footer.php'; ?>
        </div>
    </body>
</html>

<?php

use Aternos\Mclogs\Api\Response\ApiError;
use Aternos\Mclogs\Api\Response\ApiResponse;
use Aternos\Mclogs\Api\Response\MultiResponse;
use Aternos\Mclogs\Config\Config;
use Aternos\Mclogs\Config\ConfigKey;
use Aternos\Mclogs\Util\URL;

$config = Config::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title>API Documentation - <?= htmlspecialchars($config->getName()); ?></title>
        <meta name="description" content="API documentation for <?= htmlspecialchars($config->getName()); ?> - Integrate log sharing directly into your server panel or hosting software." />
    </head>
    <body>
    <?php include __DIR__ . '/parts/header.php'; ?>
            <main>
                <div class="api-docs-header">
                    <div class="api-docs-header-content">
                        <h1>API Documentation</h1>
                        <p>Integrate <strong><?= htmlspecialchars($config->getName()); ?></strong> directly into your server panel, your hosting software or anything else. This platform was built for high performance automation and can easily be integrated into any existing software via our HTTP API.</p>
                    </div>
                </div>
                <div class="api-docs-toc">
                    <h3>Quick Links</h3>
                    <nav class="api-docs-toc-nav">
                        <a href="#create-log">Create a log</a>
                        <a href="#get-log-info">Get log info and content</a>
                        <a href="#delete-log">Delete a log</a>
                    </nav>
                </div>
                <div class="api-docs-section" id="create-log">
                    <h2>Create a log</h2>

                    <div class="api-endpoint">
                        <span class="api-method">POST</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->withPath("/1/log")->toString()); ?></span> <span class="content-type">application/json</span>
                    </div>
                    <div class="api-note">
                        Posting content with the content type <span class="content-type">application/x-www-form-urlencoded</span> is still supported for backwards compatibility, but does not support setting metadata.
                    </div>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Required</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">content</td>
                            <td class="api-required required"><i class="fa-solid fa-square-check"></i></td>
                            <td class="api-type">string</td>
                            <td class="api-description">
                                The raw log file content as string.
                                Limited to <?= number_format($config->get(ConfigKey::STORAGE_LIMIT_BYTES) / 1024 / 1024, 2); ?> MiB and <?= number_format($config->get(ConfigKey::STORAGE_LIMIT_LINES)); ?> lines.
                                Will be truncated if possible and necessary, but truncating on the client side is recommended.
                            </td>
                        </tr>
                        <tr>
                            <td class="api-field">source</td>
                            <td class="api-required"><i class="fa-solid fa-square-xmark"></i></td>
                            <td class="api-type">string</td>
                            <td class="api-description">The name of the source, e.g. a domain or software name.</td>
                        </tr>
                        <tr>
                            <td class="api-field">metadata</td>
                            <td class="api-required"><i class="fa-solid fa-square-xmark"></i></td>
                            <td class="api-type">array</td>
                            <td class="api-description">An array of metadata entries.</td>
                        </tr>
                    </table>

                    <h3>Example body <span class="content-type">application/json</span></h3>
                    <pre class="api-code">{
    "content": "[log file content...]",
    "source": "example.org"
}</pre>

                    <h3>Metadata</h3>
                    <p>
                        You can send metadata alongside the log content to be displayed on the log page and/or be read by other applications through this API.
                        This is entirely optional, but can help to provide additional context, e.g. internal server IDs, software versions etc.
                    </p>
                    <p>
                        A metadata entry is an object with the following fields:
                    </p>
                    <table class="api-table">
                        <tr>
                            <th>Field</th>
                            <th>Required</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">key</td>
                            <td class="api-required required"><i class="fa-solid fa-square-check"></i></td>
                            <td class="api-type">string</td>
                            <td class="api-description">The metadata key. Can be used to identify the entry in your code later.</td>
                        </tr>
                        <tr>
                            <td class="api-field">value</td>
                            <td class="api-required required"><i class="fa-solid fa-square-check"></i></td>
                            <td class="api-type">string|int|float|bool|null</td>
                            <td class="api-description">The metadata value.</td>
                        </tr>
                        <tr>
                            <td class="api-field">label</td>
                            <td class="api-required"><i class="fa-solid fa-square-xmark"></i></td>
                            <td class="api-type">string</td>
                            <td class="api-description">The display label. If not provided, the key will be used as label.</td>
                        </tr>
                        <tr>
                            <td class="api-field">visible</td>
                            <td class="api-required"><i class="fa-solid fa-square-xmark"></i></td>
                            <td class="api-type">bool</td>
                            <td class="api-description">Whether this metadata should be visible on the log page or is only available through the API. Default is true.</td>
                        </tr>
                    </table>

                    <h3>Example body with metadata <span class="content-type">application/json</span></h3>
                    <pre class="api-code">{
    "content": "[log file content...]",
    "source": "example.org",
    "metadata": [
        {
            "key": "server_id",
            "value": 12345,
            "visible": false
        },
        {
            "key": "software_version",
            "value": "1.2.3",
            "label": "Software Version",
            "visible": true
        }
    ]
}</pre>

                    <h3>Responses</h3>
                    <h4>Success <span class="content-type">application/json</span></h4>
                    <div class="api-note">
                        The token provided in this response can be used to delete this log later. Store or discard it securely, it will not be shown again.
                    </div>
                    <pre class="api-code">{
    "success":true,
    "id":"WnMMikq",
    "source":null,
    "created":1769597979,
    "expires":1777373979,
    "size":157369,
    "lines":1201,
    "errors":8,
    "url": "<?= htmlspecialchars(URL::getBase()->withPath("/WnMMikq")->toString()); ?>",
    "raw": "<?= htmlspecialchars(URL::getApi()->withPath("/1/raw/WnMMikq")->toString()); ?>",
    "token":"78351fafe495398163fff847f9a26dda440435dcf7b5f92e8e36308f3683d771",
    "metadata": [
        {
            "key": "server_id",
            "value": 12345,
            "visible": false
        },
        {
            "key": "software_version",
            "value": "1.2.3",
            "label": "Software Version",
            "visible": true
        }
    ]
}</pre>
                    <h4>Error <span class="content-type">application/json</span></h4>
                    <pre class="api-code">
{
    "success": false,
    "error": "Required field 'content' not found."
}</pre>
                </div>

                <div class="api-docs-section" id="get-log-info">
                    <h2>Get log info and content</h2>
                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->toString()); ?>/1/log/[id]</span>
                    </div>
                    <p>
                        This endpoint only returns the log info and metadata by default (same response as creating a log), you can also get the content in the same request by enabling it in different
                        formats using GET parameters. You can combine multiple parameters to get multiple content formats in one request, but keep in mind that this will
                        increase the response size.
                    </p>
                    <table class="api-table">
                        <tr>
                            <th>GET Parameter</th>
                            <th>Response field</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">raw</td>
                            <td class="api-type">content.raw</td>
                            <td class="api-description">Includes the raw log content as string in the response.</td>
                        </tr>
                        <tr>
                            <td class="api-field">parsed</td>
                            <td class="api-type">content.parsed</td>
                            <td class="api-description">Includes the parsed log content as array/objects in the response.</td>
                        </tr>
                        <tr>
                            <td class="api-field">insights</td>
                            <td class="api-type">content.insights</td>
                            <td class="api-description">Includes the automatically detected insights in the response.</td>
                        </tr>
                    </table>
                    <h3>Responses</h3>
                    <h4>Success <span class="content-type">application/json</span></h4>
                    <div class="api-note">
                        All content fields are only included if the corresponding GET parameter is provided.
                        If no content parameter is provided, the entire content object is omitted from the response.
                    </div>
                    <pre class="api-code">{
    "success":true,
    "id":"WnMMikq",
    "source":null,
    "created":1769597979,
    "expires":1777373979,
    "size":157369,
    "lines":1201,
    "errors":8,
    "url": "<?= htmlspecialchars(URL::getBase()->withPath("/WnMMikq")->toString()); ?>",
    "raw": "<?= htmlspecialchars(URL::getApi()->withPath("/1/raw/WnMMikq")->toString()); ?>",
    "metadata": [
        {
            "key": "server_id",
            "value": 12345,
            "visible": false
        },
        {
            "key": "software_version",
            "value": "1.2.3",
            "label": "Software Version",
            "visible": true
        }
    ],
    "content": {
        "raw": "[log file content...]",
        "parsed": [ /* parsed log entries */ ],
        "insights": { "problems": [ /* detected problems */ ], "information": [ /* detected information */ ] }
    }
}</pre>
                    <h4>Error <span class="content-type">application/json</span></h4>
                    <pre class="api-code">
{
    "success": false,
    "error": "Log not found."
}</pre>
                </div>
                <div class="api-docs-section" id="delete-log">
                    <h2>Delete a log</h2>
                    <div class="api-note">
                        Deleting a log requires the token that was provided when creating the log.
                    </div>

                    <div class="api-endpoint">
                        <span class="api-method">DELETE</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->toString()); ?>/1/log/[id]</span>
                    </div>

                    <h3>Headers</h3>
                    <table class="api-table">
                        <tr>
                            <th>Header</th>
                            <th>Example</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">Authorization</td>
                            <td class="api-type">Authorization: Bearer 78351fafe495398163f...</td>
                            <td class="api-description">The type (always "Bearer") and the log token received when creating the log.</td>
                        </tr>
                    </table>

                    <h3>Responses</h3>
                    <h4>Success <span class="content-type">application/json</span></h4>
                    <pre class="api-code">{
    "success": true
}</pre>
                    <h4>Error <span class="content-type">application/json</span></h4>
                    <pre class="api-code">
{
    "success": false,
    "error": "Invalid token."
}</pre>
                </div>
                <div class="api-docs-section" id="bulk-delete-log">
                    <h2>Bulk delete multiple logs</h2>
                    <div class="api-note">
                        Deleting a log requires the token that was provided when creating the log.
                    </div>

                    <div class="api-endpoint">
                        <span class="api-method">POST</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->toString()); ?>/1/bulk/log/delete</span>
                    </div>

                    <h3>Example body <span class="content-type">application/json</span></h3>
                    <pre class="api-code"><?= json_encode([
                                [
                                        "id" => "6wexMDE",
                                        "token" => "78351fafe495398163fff847f9a26dda440435dcf7b5f92e8e36308f3683d771"
                                ],
                                [
                                        "id" => "OahzhMG",
                                        "token" => "6520dd42ec3d5fd0e83f28220974fb83d3bdc0746853f5022373f8e5b062651b"
                                ],
                        ], JSON_PRETTY_PRINT); ?></pre>

                    <h3>Responses</h3>
                    <h4>Success <span class="content-type">application/json</span></h4>
                    <pre class="api-code"><?=json_encode(new MultiResponse()
                                ->addResponse("6wexMDE", new ApiResponse())
                                ->addResponse("OahzhMG", new ApiResponse()), JSON_PRETTY_PRINT); ?></pre>
                    <h4>Error <span class="content-type">application/json</span></h4>
                    <div class="api-note">
                        If a bulk delete request is malformed of invalid, the entire request will be
                        rejected with an error response and no logs will be deleted.
                    </div>
                    <pre class="api-code">
{
    "success": false,
    "error": "No logs provided."
}</pre>
                    <h4>Partial success <span class="content-type">application/json</span></h4>
                    <div class="api-note">
                        If a bulk delete request is valid, but not all logs can be deleted (e.g. due to invalid tokens or non-existing logs),
                        the response will use the HTTP status code 207 Multi-Status and include the result for each log in the response body.
                    </div>
                    <pre class="api-code"><?=json_encode(new MultiResponse()
                                ->addResponse("6wexMDE", new ApiResponse())
                                ->addResponse("OahzhMG", new ApiError(404, "Log not found.")), JSON_PRETTY_PRINT); ?></pre>
                    <div class="api-note">
                        If the request is valid, but all deletions fail, the response will be returned as unsuccessful.
                    </div>
                    <pre class="api-code"><?=json_encode(new MultiResponse()
                                ->addResponse("6wexMDE", new ApiError(404, "Log not found."))
                                ->addResponse("OahzhMG", new ApiError(404, "Log not found.")), JSON_PRETTY_PRINT); ?></pre>
                </div>
                <div class="api-docs-section" id="get-raw">
                    <h2>Get the raw log file content</h2>
                    <div class="api-note">
                        Only use this endpoint if you really only need the raw log content. For most use cases, getting the log info and content together from the log endpoint is recommended.
                    </div>
                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->toString()); ?>/1/raw/[id]</span>
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
                            <td class="api-description">The log file id, received from the paste endpoint or from a URL (<?= htmlspecialchars(URL::getBase()->toString()); ?>/[id]).</td>
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
                <div class="api-docs-section" id="get-insights">
                    <h2>Get insights</h2>
                    <div class="api-note">
                        This endpoint is mainly kept for backwards compatibility. For new applications, getting the insights together with the log info from the log endpoint is recommended.
                    </div>
                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->toString()); ?>/1/insights/[id]</span>
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
                            <td class="api-description">The log file id, received from the paste endpoint or from a URL (<?= htmlspecialchars(URL::getBase()->toString()); ?>/[id]).</td>
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
                <div class="api-docs-section" id="analyse">
                    <h2>Analyse a log without saving it</h2>
                    <p>
                        If you only want to use the analysis features of this service without saving the log, you can use this endpoint.
                        Please do not save logs that you only want to analyse, as this wastes storage space and resources.
                    </p>

                    <div class="api-endpoint">
                        <span class="api-method">POST</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->withPath("/1/analyse")->toString()); ?></span> <span class="content-type">application/x-www-form-urlencoded</span> <span class="content-type">application/json</span>
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
                <div class="api-docs-section" id="check-limits">
                    <h2>Check storage limits</h2>

                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->withPath("/1/limits")->toString()); ?></span>
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
                <div class="api-docs-section" id="check-limits">
                    <h2>Get filters</h2>
                    <p>
                        Filters modify the log content before storing it. They are applied automatically when creating a new log on the server side.
                        You can get a list of active filters from this endpoint if you want to apply the same filters on the client side before uploading a log.
                    </p>
                    <div class="api-endpoint">
                        <span class="api-method">GET</span> <span class="api-url"><?= htmlspecialchars(URL::getApi()->withPath("/1/filters")->toString()); ?></span>
                    </div>
                    <h3>Success <span class="content-type">application/json</span></h3>
                    <pre class="api-code">
<?=htmlspecialchars(json_encode(\Aternos\Mclogs\Filter\Filter::getAll(), JSON_PRETTY_PRINT)); ?></pre>
                    <h3>Filter types</h3>
                    <table class="api-table">
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td class="api-field">trim</td>
                            <td class="api-description">
                                Trim any whitespace characters from the beginning and end of the log content.
                            </td>
                        </tr>
                        <tr>
                            <td class="api-field">limit-bytes</td>
                            <td class="api-description">
                                Limit the log content to a maximum number of bytes (data.limit). Content exceeding this limit will be truncated.
                            </td>
                        </tr>
                        <tr>
                            <td class="api-field">limit-lines</td>
                            <td class="api-description">
                                Limit the log content to a maximum number of lines (data.limit). Additional lines will be removed.
                            </td>
                        </tr>
                        <tr>
                            <td class="api-field">regex</td>
                            <td class="api-description">
                                Apply regular expression replacements to the log content. Each pattern in data.patterns will be applied in order and replaced with the provided replacement, unless the matched string matches one of the exemption patterns in data.exemptions.
                            </td>
                        </tr>
                    </table>
                    <div class="api-note">
                        Make sure to handle any filter error, e.g. unknown filter types gracefully, as new filter types may be added in the future.
                    </div>
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
    </body>
</html>

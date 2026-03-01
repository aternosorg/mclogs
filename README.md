<p align="center">
    <img src="web/public/img/logo.svg" width="260">
</p>
<p align="center">
    <strong>Paste, share & analyse your logs</strong><br>
    Game-agnostic log analysis platform
</p>

## Features
* Share logs by pasting or uploading files
* Automatic removal of sensitive information (e.g. IP addresses)
* Short URLs for easy sharing
* Syntax highlighting
* Line numbers
* Direct links to specific lines
* Analysis and parsing using [codex](https://github.com/indifferentketchup/codex)

### For developers
* Upload your logs using the API
* Add metadata to shared logs, e.g. version numbers, server ids, etc.
* Retrieve logs and their metadata from the API
* Open source and self-hostable

## Self-hosting
You can self-host IB Logs using Docker. A [docker image](https://github.com/indifferentketchup/ib-logs/pkgs/container/ib-logs) is available in the GitHub Container Registry: `ghcr.io/indifferentketchup/ib-logs`. 
A MongoDB instance is also required to run IB Logs.

An example docker compose files for self-hosting can be found here: [docker/compose.production.yaml](docker/compose.production.yaml).

### Config
You can configure IB Logs by creating a `config.json` file in the root directory, see [example.config.json](example.config.json) or by setting
environment variables. Environment variables override settings in the config file.

Here is a list of all available config options:

| Variable / JSON Path                                                | Default             | Description                                 |
|:--------------------------------------------------------------------|:--------------------|:--------------------------------------------|
| `IBLOGS_STORAGE_TTL` <br> `storage.ttl`                             | `7776000` (90d)     | Time until logs are deleted after last view |
| `IBLOGS_STORAGE_LIMIT_BYTES` <br> `storage.limit.bytes`             | `10485760` (10 MiB) | Maximum size of a log in bytes              |
| `IBLOGS_STORAGE_LIMIT_LINES` <br> `storage.limit.lines`             | `25000`             | Maximum number of lines in a log            |
| `IBLOGS_MONGODB_URL` <br> `mongodb.url`                             | `"mongodb://mongo"` | MongoDB connection URL                      |
| `IBLOGS_MONGODB_DATABASE` <br> `mongodb.database`                   | `"iblogs"`          | Name of the MongoDB database                |
| `IBLOGS_ID_LENGTH` <br> `id.length`                                 | `7`                 | The default length for new IDs              |
| `IBLOGS_LEGAL_ABUSE` <br> `legal.abuse`                             | `null`              | Public email address to report abuse        |
| `IBLOGS_LEGAL_IMPRINT` <br> `legal.imprint`                         | `null`              | The imprint URL                             |
| `IBLOGS_LEGAL_PRIVACY` <br> `legal.privacy`                         | `null`              | The privacy policy URL                      |
| `IBLOGS_FRONTEND_NAME` <br> `frontend.name`                         | `null`              | Instance name (defaults to domain)          |
| `IBLOGS_FRONTEND_COLOR_ACCENT` <br> `frontend.color.accent`         | `#5cb85c`           | The accent/primary color                    |
| `IBLOGS_FRONTEND_COLOR_BACKGROUND` <br> `frontend.color.background` | `#1a1a1a`           | The background color                        |
| `IBLOGS_FRONTEND_COLOR_TEXT` <br> `frontend.color.text`             | `#e8e8e8`           | The text color                              |
| `IBLOGS_FRONTEND_COLOR_ERROR` <br> `frontend.color.error`           | `#f62451`           | The error color                             |
| `IBLOGS_WORKER_REQUESTS` <br> `worker.requests`                     | `500`               | Max requests per single worker              |

There are a few more environment variables that can be set to modify the FrankenPHP/Caddy setup directly:

| Variable             | Default            | Description                                                                                                                                |
|----------------------|--------------------|--------------------------------------------------------------------------------------------------------------------------------------------|
| `SERVER_NAME`        | `":80"`            | Set the Caddy server name, set this to your domain for [automatic SSL](https://caddyserver.com/docs/automatic-https#hostname-requirements) |
| `TRUSTED_PROXIES`    | `"private_ranges"` | Set [trusted proxy](https://caddyserver.com/docs/caddyfile/options#trusted-proxies) address ranges                                         |
| `FRANKENPHP_WORKERS` | `16`               | The number of [FrankenPHP workers](https://frankenphp.dev/docs/worker/)                                                                    |                                                                                                                                            |


## Development setup
### Prerequisites
* [Docker](https://www.docker.com/get-started/) and [Docker Compose](https://docs.docker.com/compose/install/)
* [PHP 8.5+](https://www.php.net/downloads)
* [Composer](https://getcomposer.org/download/)

### Installation
```bash
# clone repository
git clone git@github.com:indifferentketchup/ib-logs.git

# install composer dependencies
cd ib-logs
composer install

# start development environment
cd dev
docker compose up
```
Open http://localhost in browser and enjoy.
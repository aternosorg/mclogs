<p align="center">
    <img src="web/public/img/logo.svg" width="300">
</p>
<p align="center">
    <strong>Paste, share & analyse your logs</strong><br>
    Built for Minecraft & Hytale
</p>

## Features
* Share logs by pasting or uploading files
* Automatic removal of sensitive information (e.g. IP addresses)
* Short URLs for easy sharing
* Syntax highlighting
* Line numbers
* Direct links to specific lines
* Analysis and parsing using [codex](https://github.com/aternosorg/codex-minecraft)

### For developers
* Upload your logs using the API
* Add metadata to shared logs, e.g. version numbers, server ids, etc.
* Retrieve logs and their metadata from the API
* Open source and self-hostable

## Self-hosting
You can self-host mclogs using Docker. A [docker image](https://github.com/aternosorg/mclogs/pkgs/container/mclogs) is available in the GitHub Container Registry: `ghcr.io/aternosorg/mclogs`. 
A MongoDB instance is also required to run mclogs.

An example docker compose files for self-hosting can be found here: [docker/compose.production.yaml](docker/compose.production.yaml).

### Config
You can configure mclogs by creating a `config.json` file in the root directory, see [example.config.json](example.config.json) or by setting
environment variables. Environment variables override settings in the config file.

Here is a list of all available config options:

| Environment variable               | JSON config path            | Type           | Default                   | Description                                               |
|------------------------------------|-----------------------------|----------------|---------------------------|-----------------------------------------------------------|
| `MCLOGS_STORAGE_TTL`               | `storage.ttl`               | `int`          | `7776000` (90 days)       | Time until logs are deleted after their last view         |
| `MCLOGS_STORAGE_LIMIT_BYTES`       | `storage.limit.bytes`       | `int`          | `10485760` (10 MiB)       | Maximum size of a log in bytes                            |
| `MCLOGS_STORAGE_LIMIT_LINES`       | `storage.limit.lines`       | `int`          | `25000`                   | Maximum number of lines in a log                          |
| `MCLOGS_MONGODB_URL`               | `mongodb.url`               | `string`       | `"mongodb://mongo:27017"` | MongoDB connection URL                                    |
| `MCLOGS_MONGODB_DATABASE`          | `mongodb.database`          | `string`       | `"mclogs"`                | Name of the MongoDB database                              |
| `MCLOGS_ID_LENGTH`                 | `id.length`                 | `int`          | `7`                       | The default length for new IDs                            |
| `MCLOGS_LEGAL_ABUSE`               | `legal.abuse`               | `string\|null` | `null`                    | An email address that's linked publicly to report abuse   |
| `MCLOGS_LEGAL_IMPRINT`             | `legal.imprint`             | `string\|null` | `null`                    | The imprint URL                                           |
| `MCLOGS_LEGAL_PRIVACY`             | `legal.privacy`             | `string\|null` | `null`                    | The privacy policy URL                                    |
| `MCLOGS_FRONTEND_NAME`             | `frontend.name`             | `string\|null` | `null`                    | The name of your instance, defaults to the current domain |
| `MCLOGS_FRONTEND_COLOR_ACCENT`     | `frontend.color.accent`     | `string`       | `#5cb85c`                 | The accent/primary color                                  |
| `MCLOGS_FRONTEND_COLOR_BACKGROUND` | `frontend.color.background` | `string`       | `#1a1a1a`                 | The background color                                      |
| `MCLOGS_FRONTEND_COLOR_TEXT`       | `frontend.color.text`       | `string`       | `#e8e8e8`                 | The text color                                            |
| `MCLOGS_FRONTEND_COLOR_ERROR`      | `frontend.color.text`       | `string`       | `#f62451`                 | The error color                                           |
| `MCLOGS_WORKER_REQUESTS`           | `worker.requests`           | `int`          | `500`                     | The number of requests a single worker should handle      |


There are a few more environment variables that can be set to modify the FrankenPHP/Caddy setup directly:

| Environment variable | Type     | Default | Description                                                                                                                                |
|----------------------|----------|---------|--------------------------------------------------------------------------------------------------------------------------------------------|
| `SERVER_NAME`        | `string` | `":80"` | Set the Caddy server name, set this to your domain for [automatic SSL](https://caddyserver.com/docs/automatic-https#hostname-requirements) |
| `FRANKENPHP_WORKERS` | `int`    | `16`    | The number of [FrankenPHP workers](https://frankenphp.dev/docs/worker/)                                                                    |                                                                                                                                            |


## Development setup
### Prerequisites
* [Docker](https://www.docker.com/get-started/) and [Docker Compose](https://docs.docker.com/compose/install/)
* [PHP 8.5+](https://www.php.net/downloads)
* [Composer](https://getcomposer.org/download/)

### Installation
```bash
# clone repository
git clone git@github.com:aternosorg/mclogs.git

# install composer dependencies
cd mclogs
composer install

# start development environment
cd dev
docker compose up
```
Open http://localhost in browser and enjoy.
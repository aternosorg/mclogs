FROM dunglas/frankenphp:1-php8.5

# System Setup
RUN install-php-extensions mongodb zip

ARG USER=mclogs
RUN useradd ${USER} && \
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

WORKDIR /app

# Dependencies (Cached)
COPY composer.json composer.lock ./

RUN --mount=type=cache,target=/tmp/cache/composer \
    COMPOSER_CACHE_DIR=/tmp/cache/composer \
    composer install --no-dev --no-interaction --no-scripts --no-autoloader --prefer-dist --ignore-platform-req=ext-frankenphp

# Application Setup
COPY docker/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/mclogs.ini /usr/local/etc/php/conf.d/mclogs.ini

COPY . .

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative
RUN php build.php

# Permissions & Runtime
RUN chown -R ${USER}:${USER} /config/caddy /data/caddy /app

USER ${USER}

EXPOSE 80
EXPOSE 443
EXPOSE 443/udp

VOLUME ["/data"]
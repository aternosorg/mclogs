FROM dunglas/frankenphp:1-php8.5

RUN install-php-extensions mongodb

WORKDIR /app

COPY docker/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/mclogs.ini /usr/local/etc/php/conf.d/mclogs.ini
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer/composer:2-bin /composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-frankenphp

EXPOSE 80
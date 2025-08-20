FROM php:8.1-fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libzip-dev \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*

RUN mkdir /app
WORKDIR /app

# Prepare app

COPY docker/mclogs.ini /usr/local/etc/php/conf.d/mclogs.ini
COPY api ./api
COPY core ./core

RUN mkdir -p /app/storage/logs

# Install composer dependencies

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader


ENTRYPOINT [ "php", "-S", "0.0.0.0:8080", "-t", "/app/api/public" ]
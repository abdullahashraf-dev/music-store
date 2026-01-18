FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    libxml2-dev \
    libsodium-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    gd \
    zip \
    soap \
    sodium

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=1000:1000 . .

RUN addgroup -g 1000 laravel && \
    adduser -u 1000 -G laravel -g laravel -s /bin/sh -D laravel && \
    mkdir -p storage/logs storage/app storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

USER laravel

EXPOSE 9000
CMD ["php-fpm"]
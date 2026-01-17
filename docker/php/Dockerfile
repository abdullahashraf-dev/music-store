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
    libxml2-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    gd \
    zip \
    soap

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN addgroup -g 1000 laravel && \
    adduser -u 1000 -G laravel -g laravel -s /bin/sh -D laravel

RUN chown -R laravel:laravel /var/www/html


USER laravel

EXPOSE 9000
CMD ["php-fpm"]
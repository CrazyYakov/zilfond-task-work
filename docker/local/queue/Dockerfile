ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-alpine

ARG APP_ENV="dev"

RUN apk update && apk add --no-cache \
    zip \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    curl \
    postgresql-dev \
    build-base \
    autoconf \
    libtool\
    gcc \
    make

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN curl -fL --output /usr/local/bin/pie https://github.com/php/pie/releases/latest/download/pie.phar \
    && chmod +x /usr/local/bin/pie

RUN docker-php-ext-install pdo pdo_pgsql pgsql && \
    pie install phpredis/phpredis
RUN docker-php-ext-enable pdo pgsql

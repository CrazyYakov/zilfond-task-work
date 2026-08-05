ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-fpm-alpine

ARG USER_ID=1000
ARG GROUP_ID=1000
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

RUN if [ ${APP_ENV} != "production" ]; then \
      apk add --no-cache $PHPIZE_DEPS linux-headers; \
      pie install xdebug/xdebug; \
      pie install pecl/pcov; \
    fi

RUN addgroup -g ${GROUP_ID} www && \
    adduser -D -u ${USER_ID} -G www -s /bin/bash www;

USER ${USER_ID}:${GROUP_ID}

EXPOSE 9000

CMD php-fpm

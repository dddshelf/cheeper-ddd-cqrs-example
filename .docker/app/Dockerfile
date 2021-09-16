FROM php:8.0-alpine

RUN apk update && \
    apk add git icu-dev $PHPIZE_DEPS && \
    docker-php-ext-install bcmath pdo_mysql pcntl posix mysqli intl && \
    pecl install redis && \
    docker-php-ext-enable redis

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

COPY 99-app.ini /usr/local/etc/php/conf.d/

WORKDIR /app

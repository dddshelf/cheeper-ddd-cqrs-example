FROM php:8.0-fpm-alpine

RUN apk update && \
    apk add icu-dev && \
    docker-php-ext-install bcmath pdo_mysql pcntl posix mysqli intl

COPY 99-app.ini /usr/local/etc/php/conf.d/

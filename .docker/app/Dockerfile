FROM php:8.0-alpine

RUN apk update && \
    apk add git icu-dev rabbitmq-c rabbitmq-c-dev $PHPIZE_DEPS && \
    docker-php-ext-install bcmath pdo_mysql pcntl posix mysqli intl && \
    pecl install redis amqp-beta xdebug && \
    docker-php-ext-enable redis amqp xdebug

COPY 99-app.ini /usr/local/etc/php/conf.d/

WORKDIR /app

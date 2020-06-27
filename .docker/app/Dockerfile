FROM php:7.4-alpine

RUN apk add --no-cache --update git

RUN docker-php-ext-install bcmath pdo_mysql pcntl posix mysqli

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

COPY 99-app.ini /usr/local/etc/php/conf.d/

WORKDIR /app

FROM php:7.4-fpm-alpine

RUN docker-php-ext-install bcmath pdo_mysql pcntl posix mysqli

COPY 99-app.ini /usr/local/etc/php/conf.d/

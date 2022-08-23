# syntax=docker/dockerfile:1.4
FROM php:8.1-apache AS prod

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN apt-get update \
    && apt-get -y install librabbitmq-dev \
                          libicu-dev \
                          $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql mysqli pcntl bcmath intl \
    && pecl install redis \
    && pecl install amqp \
    && docker-php-ext-enable redis amqp \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

FROM prod AS local

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
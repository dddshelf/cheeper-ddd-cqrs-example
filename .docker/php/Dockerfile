FROM php:8.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN apt-get update \
    && apt-get -y install librabbitmq-dev \
                          $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql mysqli pcntl \
    && pecl install redis \
    && pecl install amqp \
    && docker-php-ext-enable redis amqp \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite
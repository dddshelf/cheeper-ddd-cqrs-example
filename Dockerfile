FROM php:7.4-alpine AS base

RUN apk add --no-cache --update git

RUN docker-php-ext-install bcmath pdo_mysql pcntl posix mysqli

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /app

COPY src src
COPY config config
COPY public public
COPY templates templates
COPY composer.lock ./
COPY composer.json ./
COPY symfony.lock ./
COPY .env ./
COPY .env.test ./

FROM base AS test

WORKDIR /app

COPY bin bin
COPY tests tests
COPY .psalm-stubs .psalm-stubs
COPY .php_cs.dist ./
COPY psalm.xml ./
COPY phpunit.xml.dist ./
RUN composer install

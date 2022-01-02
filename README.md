<h1 align="center">
    <a href="https://leanpub.com/cqrs-by-example">
        <img src="https://gitmood.app/assets/images/cheeper.svg" width="150" alt="cheeper logo">
    </a>
    <br>
    Cheeper
</h1>

<h4 align="center">Cheeper is a Twitter clone used in the book <a href="https://leanpub.com/cqrs-by-example/">CQRS By Example</a> as a reference implementation.</h4>

<p align="center">
    <a href="https://github.com/cqrs-by-example/cheeper/actions"><img src="https://github.com/cqrs-by-example/cheeper/workflows/CI/badge.svg?branch=master" alt="CI status" /></a>
</p>

## How to run the application

### Requirements

* Just install [docker](https://docs.docker.com/get-docker/).
* If you want to run PHP locally and leave docker for just external services (mysql, redis, elastic and rabbitmq): 
    * Make sure to have a local PHP installation. The minimum PHP version needed is 7.4.1.
    * Needed PHP extensions: bcmath, pdo_mysql, pcntl, posix, mysqli.
    * Make sure to install [Symfony CLI](https://symfony.com/download).

### 🐳 Full Docker

This code can run fully on docker. In order to do so just run

    make run

To start the development environment. If this is the first time you run the code you will need to run database migrations

    make database

And to stop all services just run

    make stop

### Local Symfony Webserver + Docker for external services

This code can also be run using Symfony Local Webserver.


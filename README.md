<h1 align="center">
    <a href="https://leanpub.com/cqrs-by-example">
        <img src="https://raw.githubusercontent.com/dddshelf/cheeper-ddd-cqrs-example/main/public/cheeper.svg" width="150" alt="cheeper logo">
    </a>
    <br>
    Hexagonal Architecture + DDD + CQRS Workshop
</h1>

<p align="center">
    <a href="https://github.com/dddshelf/cheeper-ddd-cqrs-example/actions"><img src="https://github.com/dddshelf/cheeper-ddd-cqrs-example/workflows/CI/badge.svg?branch=workflow" alt="CI status" /></a>
    <img src="https://img.shields.io/static/v1?label=PHP&message=8.1&color=blueviolet" alt="PHP Version" />
    <img src="https://img.shields.io/static/v1?label=Symfony&message=6.1&color=informational" alt="Symfony Version" />
    <img src="https://img.shields.io/static/v1?label=Built+with&message=%E2%9D%A4%EF%B8%8F&color=FDE0D9" alt="Built with love" />
</p>

<p align="justify">
    Cheeper is a Twitter clone used in the book <a href="https://leanpub.com/cqrs-by-example/">CQRS By Example</a> as a reference implementation. In this branch you will find an starter code for a <strong>Hexagonal Architecture</strong>, <strong>DDD (tactical patterns)</strong> and <strong>CQRS (Command query responsibility segregation)</strong> workshop.
</p>

## Code Structure

This repository follows a typical PHP application layout: All the code lives in the `src` folder and classes map 1:1 with the file where they live, following the [PSR-4](https://www.php-fig.org/psr/psr-4/) standard for class autoloading.

### The `App` namespace

This namespace contains all the classes that are specific to the delivery mechanism. In this case the delivery mechanism is the [Symfony framework](https://symfony.com/) following the recommended structure.

### The `Cheeper` namespace

    src/Cheeper
    ├── Application 👉 Contains Application Services
    ├── DomainModel 👉 Contains all the domain model classes
    └── Infrastructure 👉 Contains all the domain-related infrastructure

## Running the application

### Requirements

* [Docker](https://docs.docker.com/get-docker/) 👉 To run all services in your computer without installing all the dependencies.
* [Docker Compose](https://docs.docker.com/compose/) 👉 To orchestrate the services. If you're on Mac or Windows and you already have [Docker Desktop](https://docs.docker.com/desktop/) installed, you already have Docker Compose installed. If you're on Linux, you can check out installation [here](https://docs.docker.com/compose/install/).

### Booting up 🚀

The first thing you should do is to have a fresh copy of the code. To do so just clone the repository and switch to the `workshop` branch.

    git clone https://github.com/dddshelf/cheeper-ddd-cqrs-example.git
    git checkout -b workshop

Then, in order to get environment set up properly, you should have a `.env.local` file with all the correct values 👇

    cp .env.docker.dist .env.local

As the code runs fully on docker, several `make` targets have been carefully prepared in order to make the interaction with the code as easy and smooth as possible. To start all the services just run 👇

    make start

This `make` target, runs docker compose, and ensures all services are up and running. Next, once all the services have been started, we must make sure the database is created, database migrations are executed successfully and fixtures are loaded properly. To do so, just run

    make refresh-fixtures

Finally to stop all services just run

    make stop

Now you're ready to go with the workshop! 🚀

### Accessing the application

From here you can access to the Cheeper API documentation

**https://127.0.0.1:8000/api/docs**

### Running tests

This code is covered by a suite of unit & functional tests and a mutation coverage score of **100%** on the `Cheeper\Application` namespace. To run the tests 👇

    make unit-tests # 👉 Runs the unit test suite
    make functional-tests # 👉 Runs the functional test suite
    make mutation-tests # 👉 Runs the mutation tests

### Static analysis

This code is statically analysed by **[psalm](https://psalm.dev)**, the coding style is inspected by **[php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)**, and the layer dependencies are monitored using **[deptrack](https://qossmic.github.io/deptrac/)**. In order to run the whole static analysys 👇

    make local-ci

Or if you prefer to run one by one

    make unit-tests
    make psalm
    make check-cs
    make deptrack
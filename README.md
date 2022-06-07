<h1 align="center">
    <a href="https://leanpub.com/cqrs-by-example">
        <img src="https://gitmood.app/assets/images/cheeper.svg" width="150" alt="cheeper logo">
    </a>
    <br>
    Hexagonal Architecture + DDD + CQRS by Example Book code repository
</h1>

<p align="center">
    <a href="https://github.com/dddshelf/cheeper-ddd-cqrs-example/actions"><img src="https://github.com/dddshelf/cheeper-ddd-cqrs-example/workflows/CI/badge.svg?branch=main" alt="CI status" /></a>
    <img src="https://img.shields.io/static/v1?label=PHP&message=8.1&color=blueviolet" alt="PHP Version" />
    <img src="https://img.shields.io/static/v1?label=Symfony&message=6.0&color=informational" alt="Symfony Version" />
    <img src="https://img.shields.io/static/v1?label=Built+with&message=%E2%9D%A4%EF%B8%8F&color=FDE0D9" alt="Built with love" />
</p>

<p align="justify">
    Cheeper is a Twitter clone used in the book <a href="https://leanpub.com/cqrs-by-example/">CQRS By Example</a> as a reference implementation. Here you will find an example of applying <strong>Hexagonal Architecture</strong>, <strong>DDD (tactical patterns)</strong> and <strong>CQRS (Command query responsibility segregation)</strong> in a real use case <strong>PHP</strong> application.
</p>
<p align="center">
  Take a look around, play a bit with the code, and if you like what you see ... 
  <a href="https://github.com/dddshelf/cheeper-ddd-cqrs-example/stargazers">Give us a star 💫</a>
  <br />
  <br />
  <strong><a href="https://leanpub.com/cqrs-by-example/">📕 Check out the book</a></strong>
  ·
  <a href="https://github.com/dddshelf/cqrs-by-example-book-issues/issues">Report an issue in the book</a>
  ·
  <a href="https://github.com/dddshelf/cheeper-ddd-cqrs-example/issues">Report an issue in the code</a>
</p>

## Table of contents

* [About the book](#about-the-book)
* [Code structure](#code-structure)
  * [The `App` namespace](#the-app-namespace)
  * [The `Cheeper` namespace](#the-cheeper-namespace)
* [Running the application](#running-the-application)
    * [Requirements](#requirements)
    * [Getting started](#getting-started)
    * [Set all the infrastructure up](#set-all-the-infrastructure-up)
    * [Demo time!](#demo-time)
        * [1. Followers counter query](#1-follower-counters-query)
        * [2. Author timeline](#2-author-timeline)
        * [3. Adding authors](#3-adding-authors)
        * [4. Consuming events](#4-consuming-events)
        * [5. Follower counters query](#5-follower-counters-query)
        * [6. Author timeline again](#6-author-timeline-again)
        * [7. Following other authors](#7-following-other-authors)
        * [8. Consuming events again](#8-consuming-events-again)
        * [9. Follower counters query](#9-follower-counters-query)
        * [10. Posting cheeps](#10-posting-cheeps)
        * [11. Author timeline](#11-author-timeline)

## About the Book

Command-Query Responsibility Segregation is an architectural style for developing applications that split the Domain Model in Read and Write operations in order to maximize semantics, performance, and scalability. What are all the benefits of CQRS? What are the drawbacks? In which cases does it worth applying it? How does it relate to Hexagonal Architecture? How do we properly implement the Write Model and Read Models? How do we keep in sync both sides? What are the following steps to move towards Event Sourcing? This book will answer all these questions and many more, guided through lots of practical examples. Brought to you by the same authors behind "Domain-Driven Design in PHP".

Do you want to know more? Do you want to grasp further details about **CQRS**?

🌟 [Go checkout the book](https://leanpub.com/cqrs-by-example/) 🌟

## Code Structure

This repository follows a typical PHP application layout: All the code lives in the `src` folder and classes map 1:1 with the file where they live, following the [PSR-4](https://www.php-fig.org/psr/psr-4/) standard for class autoloading.

### The `App` namespace

This namespace contains all the classes that are specific to the delivery mechanism. In this case the delivery mechanism is the [Symfony framework](https://symfony.com/) following the recommended structure.

### The `Cheeper` namespace

The `Cheeper` namespaces is a bit little different from a typical PHP application using tactical Domain-Driven design patterns, where you could expect several modules under the `Cheeper` namespace. In here you will find book-chapter namespaces instead of modules, so it's easy for you to navigate through all the code of each chapter.

    src/Cheeper
    ├── AllChapters
    │   ├── Application
    │   ├── DomainModel
    │   └── Infrastructure
    ├── Chapter2
    │   ├── Author.php
    │   ├── Cheep.php
    │   ├── Hexagonal
    │   ├── Layered
    │   └── Spaghetti
    ├── Chapter4
    │   ├── Application
    │   ├── DomainModel
    │   └── Infrastructure
    ├── Chapter5
    │   ├── Application
    │   └── Infrastructure
    ├── Chapter6
    │   ├── Application
    │   └── Infrastructure
    ├── Chapter7
    │   ├── Application
    │   ├── DomainModel
    │   └── Infrastructure
    ├── Chapter8
    │   ├── Application
    │   ├── DomainModel
    │   └── Infrastructure
    └── Chapter9
        ├── DomainModel
        └── Infrastructure

## Running the application

### Requirements

* [Docker](https://docs.docker.com/get-docker/) 👉 To run all services in your computer without installing all the dependencies.
* [Docker Compose](https://docs.docker.com/compose/) 👉 To orchestrate the services. If you're on Mac or Windows and you have installed [Docker Desktop](https://docs.docker.com/desktop/) you already have Docker Compose installed. If you're on Linux, you can check out installation [here](https://docs.docker.com/compose/install/).
* [HTTPie](https://httpie.io/cli) 👉 To make HTTP requests in the same way as they're done in this README file.

### Getting started

The first thing you should do is to have a fresh copy of the code. To do so just clone the repository

    git clone https://github.com/dddshelf/cheeper-ddd-cqrs-example

Then, in order to get environment set up properly, you should have a `.env.local` file with all the correct values 👇

    cp .env.docker.dist .env.local

And you're ready to go! 🤘

### Set all the infrastructure up 

As the code runs fully on docker, several `make` targets have been carefully prepared in order to make the interaction with the code as easy and smooth as possible. To start all the services just run 👇

    make start

This `make` target, runs docker compose, and ensures all services are up and running. Next, once all the services have been started, we must make sure the database and queues start empty and database migrations are executed successfully. To do so, just run

    make infrastructure

Finally to stop all services just run

    make stop

Now you're ready to execute all the demo steps! 🚀

### Demo time!

Cheeper starts totally as a blank application. There is no Author, Cheeps, Follows, etc.

#### 1. Follower Counters Query

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/followers-counter
    http --json --check-status --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/followers-counter

Expected output

    {
        "data": {
            "message": "Author \"a64a52cc-3ee9-4a15-918b-099e18b43119\" does not exist"
        },
        "meta": {
            "message_id": "c0342dfe-782e-4ffa-9e9c-8d6d82533ab2"
        }
    }

    {
        "data": {
            "message": "Author \"1fd7d739-2ad7-41a8-8c18-565603e3733f\" does not exist"
        },
        "meta": {
            "message_id": "35089f63-53ca-4a44-9a7c-6e588ba6d885"
        }
    }

    {
        "data": {
            "message": "Author \"1da1366f-b066-4514-9b29-7346df41e371\" does not exist"
        },
        "meta": {
            "message_id": "d07a6984-abc9-4a3a-92bb-829ad5dd85df"
        }
    }

#### 2. Author Timeline

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline

Expected output

    {
        "_meta": [],
        "data": "Author \"1da1366f-b066-4514-9b29-7346df41e371\" does not exist"
    }
    ...

#### 3. Adding Authors

    http --json --body POST http://127.0.0.1:8000/chapter7/author author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" username="bob" email="bob@bob.com"
    http --json --body POST http://127.0.0.1:8000/chapter7/author author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" username="alice" email="alice@alice.com"
    http --json --body POST http://127.0.0.1:8000/chapter7/author author_id="1da1366f-b066-4514-9b29-7346df41e371" username="charlie" email="charlie@charlie.com"

Expected output

    {
        "author_id": "a64a52cc-3ee9-4a15-918b-099e18b43119",
        "message_id": "d3c90181-c2c1-4e5c-90e9-99a799197b88"
    }
    ...

#### 4. Consuming Events

    php bin/console messenger:consume events_async

Expected output

    08:59:13 INFO      [messenger] Received message Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned"]
    08:59:13 INFO      [messenger] Message Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned handled by Cheeper\Chapter7\Infrastructure\Application\Author\Event\SymfonyNewAuthorSignedEventHandler::handle ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned","handler" => "Cheeper\Chapter7\Infrastructure\Application\Author\Event\SymfonyNewAuthorSignedEventHandler::handle"]
    08:59:13 INFO      [messenger] Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned was handled successfully (acknowledging to transport). ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned"]
    08:59:13 INFO      [messenger] Received message Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned"]
    08:59:13 INFO      [messenger] Message Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned handled by Cheeper\Chapter7\Infrastructure\Application\Author\Event\SymfonyNewAuthorSignedEventHandler::handle ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned","handler" => "Cheeper\Chapter7\Infrastructure\Application\Author\Event\SymfonyNewAuthorSignedEventHandler::handle"]
    08:59:13 INFO      [messenger] Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned was handled successfully (acknowledging to transport). ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned"]
    08:59:13 INFO      [messenger] Received message Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned"]
    08:59:13 INFO      [messenger] Message Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned handled by Cheeper\Chapter7\Infrastructure\Application\Author\Event\SymfonyNewAuthorSignedEventHandler::handle ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned","handler" => "Cheeper\Chapter7\Infrastructure\Application\Author\Event\SymfonyNewAuthorSignedEventHandler::handle"]
    08:59:13 INFO      [messenger] Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned was handled successfully (acknowledging to transport). ["message" => Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned^ { …},"class" => "Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned"]

#### 5. Follower Counters Query

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/followers-counter
    http --json --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/followers-counter

Expected output

    {
        "_meta": {
            "message_id": "8db6fc1d-41d5-4f6b-8950-e5766eee53e9"
        },
        "data": {
            "authorId": "1da1366f-b066-4514-9b29-7346df41e371",
            "authorUsername": "charlie",
            "numberOfFollowers": 0
        }
    }
    ...

#### 6. Author Timeline again

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline

Expected output

    {
        "cheeps": []
    }

#### 7. Following other Authors

    http --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id="8cc71bf2-f827-4c92-95a5-43bb1bc622ad" from_author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" to_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119"
    http --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id="f3088920-841e-4577-a3c2-efdc80f0dea5" from_author_id="1da1366f-b066-4514-9b29-7346df41e371" to_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119"

#### 8. Consuming events again

    php bin/console messenger:consume events_async

#### 9. Follower Counters Query

    http --json http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
    http --json http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/followers-counter
    http --json http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/followers-counter

Expected output

    {
        "_meta": {
            "message_id": "0e9df870-894f-4d37-a140-4d598941fa4f"
        },
        "data": {
            "authorId": "a64a52cc-3ee9-4a15-918b-099e18b43119",
            "authorUsername": "bob",
            "numberOfFollowers": 1
        }
    }

#### 10. Posting Cheeps

    http --json --body POST http://127.0.0.1:8000/chapter7/cheep cheep_id="28bc90bd-2dfb-4b71-962f-81f02b0b3149" author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" message="Hello world, this is Bob"
    http --json --body POST http://127.0.0.1:8000/chapter7/cheep cheep_id="04efc3af-59a3-4695-803f-d37166c3af56" author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" message="Hello world, this is Alice"
    http --json --body POST http://127.0.0.1:8000/chapter7/cheep cheep_id="8a5539e6-3be2-4fa7-906e-179efcfca46b" author_id="1da1366f-b066-4514-9b29-7346df41e371" message="Hello world, this is Charlie"

#### 11. Author Timeline

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline

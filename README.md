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

### Fixtures 

    make database

#### Adding Authors

    http --json --verify false POST https://127.0.0.1:8000/chapter7/author author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" username="bob" email="bob@bob.com"
    http --json --verify false POST https://127.0.0.1:8000/chapter7/author author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" username="alice" email="alice@alice.com"
    http --json --verify false POST https://127.0.0.1:8000/chapter7/author author_id="1da1366f-b066-4514-9b29-7346df41e371" username="charlie" email="charlie@charlie.com"

#### Posting Cheeps

    http --json --verify false POST https://127.0.0.1:8000/chapter7/cheep cheep_id="28bc90bd-2dfb-4b71-962f-81f02b0b3149" author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" message="Hello world! This is Bob!"
    http --json --verify false POST https://127.0.0.1:8000/chapter7/cheep cheep_id="04efc3af-59a3-4695-803f-d37166c3af56" author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" message="Hello world! This is Alice!"
    http --json --verify false POST https://127.0.0.1:8000/chapter7/cheep cheep_id="8a5539e6-3be2-4fa7-906e-179efcfca46b" author_id="1da1366f-b066-4514-9b29-7346df41e371" message="Hello world! This is Charlie!"

#### Following other Authors

    http --json --verify false POST https://127.0.0.1:8000/chapter7/follow follow_id="8cc71bf2-f827-4c92-95a5-43bb1bc622ad" from_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" to_author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f"
    http --json --verify false POST https://127.0.0.1:8000/chapter7/follow follow_id="f3088920-841e-4577-a3c2-efdc80f0dea5" from_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" to_author_id="1da1366f-b066-4514-9b29-7346df41e371"
    http --json --verify false POST https://127.0.0.1:8000/chapter7/follow follow_id="45ea9e38-d821-4c8c-8619-362bf57f4c56" from_author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" to_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119"

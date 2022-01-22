<h1 align="center">
    <a href="http://leanpub.com/cqrs-by-example">
        <img src="http://gitmood.app/assets/images/cheeper.svg" width="150" alt="cheeper logo">
    </a>
    <br>
    Cheeper
</h1>

<h4 align="center">Cheeper is a Twitter clone used in the book <a href="http://leanpub.com/cqrs-by-example/">CQRS By Example</a> as a reference implementation.</h4>

<p align="center">
    <a href="http://github.com/cqrs-by-example/cheeper/actions"><img src="http://github.com/cqrs-by-example/cheeper/workflows/CI/badge.svg?branch=master" alt="CI status" /></a>
</p>

## How to run the application

### Requirements

* Just install [docker](http://docs.docker.com/get-docker/).
* If you want to run PHP locally and leave docker for just external services (mysql, redis, elastic and rabbitmq): 
    * Make sure to have a local PHP installation. The minimum PHP version needed is 7.4.1.
    * Needed PHP extensions: bcmath, pdo_mysql, pcntl, posix, mysqli.
    * Make sure to install [Symfony CLI](http://symfony.com/download).

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

#### Follower Counters Query

    http --json http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
    http --json http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/followers-counter
    http --json http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/followers-counter

#### Adding Authors

    http --json POST http://127.0.0.1:8000/chapter7/author author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" username="bob" email="bob@bob.com"
    http --json POST http://127.0.0.1:8000/chapter7/author author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" username="alice" email="alice@alice.com"
    http --json POST http://127.0.0.1:8000/chapter7/author author_id="1da1366f-b066-4514-9b29-7346df41e371" username="charlie" email="charlie@charlie.com"

#### Follower Counters Query

    http --json http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
    http --json http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/followers-counter
    http --json http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/followers-counter

#### Following other Authors

    http --json POST http://127.0.0.1:8000/chapter7/follow follow_id="8cc71bf2-f827-4c92-95a5-43bb1bc622ad" from_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" to_author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f"
    http --json POST http://127.0.0.1:8000/chapter7/follow follow_id="f3088920-841e-4577-a3c2-efdc80f0dea5" from_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" to_author_id="1da1366f-b066-4514-9b29-7346df41e371"
    http --json POST http://127.0.0.1:8000/chapter7/follow follow_id="45ea9e38-d821-4c8c-8619-362bf57f4c56" from_author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" to_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119"

#### Posting Cheeps

    http --json POST http://127.0.0.1:8000/chapter7/cheep cheep_id="28bc90bd-2dfb-4b71-962f-81f02b0b3149" author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" message="Hello world, this is Bob"
    http --json POST http://127.0.0.1:8000/chapter7/cheep cheep_id="04efc3af-59a3-4695-803f-d37166c3af56" author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" message="Hello world, this is Alice"
    http --json POST http://127.0.0.1:8000/chapter7/cheep cheep_id="8a5539e6-3be2-4fa7-906e-179efcfca46b" author_id="1da1366f-b066-4514-9b29-7346df41e371" message="Hello world, this is Charlie"


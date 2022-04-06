<h1 align="center">
    <a href="http://leanpub.com/cqrs-by-example">
        <img src="http://gitmood.app/assets/images/cheeper.svg" width="150" alt="cheeper logo">
    </a>
    <br>
    CQRS by Example Book code repository
</h1>

<h4 align="center">Cheeper is a Twitter clone used in the book <a href="http://leanpub.com/cqrs-by-example/">CQRS By Example</a> as a reference implementation.</h4>

<p align="center">
    <a href="http://github.com/cqrs-by-example/cheeper/actions"><img src="http://github.com/cqrs-by-example/cheeper/workflows/CI/badge.svg?branch=master" alt="CI status" /></a>
    <img src="https://img.shields.io/static/v1?label=PHP&message=8.1&color=blueviolet" alt="PHP Version" />
    <img src="https://img.shields.io/static/v1?label=Symfony&message=6.0&color=informational" alt="Symfony Version" />
    <img src="https://img.shields.io/static/v1?label=Built-with&message=%E2%9D%A4%EF%B8%8F&color=FDE0D9" alt="Built with love" />
</p>

## How to run the application

### Requirements

* Just install [docker](http://docs.docker.com/get-docker/).
* If you want to run PHP locally and leave docker for just external services (mysql, redis, elastic and rabbitmq): 
    * Make sure to have a local PHP installation. The minimum PHP version needed is 8.1.
    * Needed PHP extensions: pdo_mysql, mysqli, amqp.
    * Make sure to install [Symfony CLI](http://symfony.com/download).

### 🐳 Full Docker

This code can run fully on docker. In order to do so just run

    make start

To start the development environment. If this is the first time you run the code you will need to run database migrations

    make infrastructure

And to stop all services just run

    make stop

Cheeper starts totally as a blank application. There is no Author, Cheeps, Follows, etc. Let's see what happens with the existing Projections when 

#### Follower Counters Query

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/followers-counter
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/followers-counter
    http --json --check-status --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/followers-counter

Expected output

    {
        "_meta": [],
        "data": "Author \"a64a52cc-3ee9-4a15-918b-099e18b43119\" does not exist"
    }

#### Author Timeline

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline

Expected output

    {
        "_meta": [],
        "data": "Author \"1da1366f-b066-4514-9b29-7346df41e371\" does not exist"
    }
    ...

#### Adding Authors

Cheeper is configured so signing up new Authors is a synchronous operations. This mean that once the HTTP request made to the Cheeper API is finished, the Author will be in the database without having to perform any other action. As soon the API receives the request, the SignUpCommand is created and delegated to the Command Bus. The Command Bus will check the routing configurations and it will pass the Command to the SignUpCommandHandler that will perform all the actions required to sign up a new Author.

    http --json --body POST http://127.0.0.1:8000/chapter7/author author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" username="bob" email="bob@bob.com"
    http --json --body POST http://127.0.0.1:8000/chapter7/author author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" username="alice" email="alice@alice.com"
    http --json --body POST http://127.0.0.1:8000/chapter7/author author_id="1da1366f-b066-4514-9b29-7346df41e371" username="charlie" email="charlie@charlie.com"

Expected output

    {
        "author_id": "a64a52cc-3ee9-4a15-918b-099e18b43119",
        "message_id": "d3c90181-c2c1-4e5c-90e9-99a799197b88"
    }
    ...

The response to the HTTP request and the Authors in the database are not the only outcome generated. In RabbitMQ, there are store multiple events of type `NewAuthorSigned`. Those Domain Events are waiting to be handled. In order to do so, they will have to be consumed. Until not getting consumed, the Follower Counters Query will still return a 404 HTTP Status Code.

#### Consuming Events

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

There is one `NewAuthorSigned` event to handle for each new Author signed in. That's a total of three. In Cheeper, the `NewAuthorSigned` is handled by the `SymfonyNewAuthorSignedEventHandler` class. Chapter 6 showed that `SymfonyNewAuthorSignedEventHandler` is 

Now, that all the Domain Events are processed, and their corresponding Projections are calculated, the queries to check how many followers - an Author has - are not empty anymore.

#### Follower Counters Query

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

#### Author Timeline

    http --json --body http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
    http --json --body http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline

Expected output

    {
        "cheeps": []
    }

#### Following other Authors

Following an Author is a Command that is defined as asynchronous. This means that once the request to the Cheeper API is done, the Command will wait to be processed by a worker. The initial HTTP request to the API returns really fast, but the changes in the database will not be done until the Command is processed.

    http --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id="8cc71bf2-f827-4c92-95a5-43bb1bc622ad" from_author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" to_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119"
    http --json --body POST http://127.0.0.1:8000/chapter7/follow follow_id="f3088920-841e-4577-a3c2-efdc80f0dea5" from_author_id="1da1366f-b066-4514-9b29-7346df41e371" to_author_id="a64a52cc-3ee9-4a15-918b-099e18b43119"

# consume

    php bin/console messenger:consume events_async

#### Follower Counters Query

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

#### Posting Cheeps

    http --json --body POST http://127.0.0.1:8000/chapter7/cheep cheep_id="28bc90bd-2dfb-4b71-962f-81f02b0b3149" author_id="a64a52cc-3ee9-4a15-918b-099e18b43119" message="Hello world, this is Bob"
    http --json --body POST http://127.0.0.1:8000/chapter7/cheep cheep_id="04efc3af-59a3-4695-803f-d37166c3af56" author_id="1fd7d739-2ad7-41a8-8c18-565603e3733f" message="Hello world, this is Alice"
    http --json --body POST http://127.0.0.1:8000/chapter7/cheep cheep_id="8a5539e6-3be2-4fa7-906e-179efcfca46b" author_id="1da1366f-b066-4514-9b29-7346df41e371" message="Hello world, this is Charlie"

#### Author Timeline

    http --json http://127.0.0.1:8000/chapter7/author/a64a52cc-3ee9-4a15-918b-099e18b43119/timeline
    http --json http://127.0.0.1:8000/chapter7/author/1fd7d739-2ad7-41a8-8c18-565603e3733f/timeline
    http --json http://127.0.0.1:8000/chapter7/author/1da1366f-b066-4514-9b29-7346df41e371/timeline

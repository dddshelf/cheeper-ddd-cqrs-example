<?php

//snippet symfony-messenger-from-scratch
declare(strict_types=1);

//ignore
use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

//end-ignore

$connection = DriverManager::getConnection([/** ... */]);
$entityManager = EntityManager::create($connection, new Configuration());
$authorsRepository = new DoctrineOrmAuthors($entityManager);
$cheepsRepository = new DoctrineOrmCheeps($entityManager);

$postCheepHandler = new PostCheepHandler(
    $authorsRepository,
    $cheepsRepository
);

$bus = new MessageBus([
    new HandleMessageMiddleware(new HandlersLocator([
        PostCheep::class => [$postCheepHandler]
    ]))
]);

$bus->dispatch(
    PostCheep::fromArray([
        'author_id' => '527cab4c-30a8-4d6a-bf7a-157910d569e5',
        'cheep_id' => '719ac125-83a9-4d6e-94da-493891b8f8b2',
        'message' => 'New cheep!'
    ])
);
//end-snippet

<?php

declare(strict_types=1);

namespace App\Messenger;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\ORM\Configuration;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class FromScratch
{
    public function __invoke(): void
    {
        //snippet symfony-messenger-from-scratch
        $connection = \Doctrine\DBAL\DriverManager::getConnection([/** ... */]);
        $entityManager = \Doctrine\ORM\EntityManager::create($connection, new Configuration());
        $authorsRepository = new DoctrineOrmAuthors($entityManager);
        $cheepsRepository = new DoctrineOrmCheeps($entityManager);
        $eventBus = new InMemoryEventBus();

        $postCheepHandler = new PostCheepHandler(
            $authorsRepository,
            $cheepsRepository,
            $eventBus,
        );

        $bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                PostCheep::class => [$postCheepHandler],
            ])),
        ]);

        $bus->dispatch(
            PostCheep::fromArray([
                'author_id' => '527cab4c-30a8-4d6a-bf7a-157910d569e5',
                'cheep_id' => '719ac125-83a9-4d6e-94da-493891b8f8b2',
                'message' => 'New Cheep!',
            ])
        );
        //end-snippet
    }
}

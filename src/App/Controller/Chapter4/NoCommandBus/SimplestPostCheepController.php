<?php

declare(strict_types=1);

namespace App\Controller\Chapter4\NoCommandBus;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\Chapter6\Infrastructure\Application\Event\SymfonyEventBus;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class SimplestPostCheepController extends AbstractController
{
    #[Route("/chapter4/cheeps", name: "chapter4_nocommand_bus_simplest_post_cheep")]
    public function __invoke(Request $request, MessageBusInterface $eventBus): Response
    {
        //snippet simple-command-handler-execution
        $authorId = $request->request->get('author_id');
        $cheepId = $request->request->get('cheep_id');
        $message = $request->request->get('message');

        if (null === $authorId || null === $cheepId || null === $message) {
            throw new BadRequestHttpException('Invalid parameters given');
        }

        $command = PostCheep::fromArray([
            'author_id' => $authorId,
            'cheep_id' => $cheepId,
            'message' => $message,
        ]);

        $connection = \Doctrine\DBAL\DriverManager::getConnection([/** ... */]);
        $entityManager = \Doctrine\ORM\EntityManager::create(
            $connection,
            new \Doctrine\ORM\Configuration()
        );

        $postCheepHandler = new PostCheepHandler(
            new DoctrineOrmAuthors($entityManager),
            new DoctrineOrmCheeps($entityManager),
            new SymfonyEventBus($eventBus),
        );

        try {
            ($postCheepHandler)($command);
        } catch (AuthorDoesNotExist | InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
        //end-snippet

        return new Response('', Response::HTTP_CREATED);
    }
}

<?php

declare(strict_types=1);

namespace CheeperCommandBus\SimplestNoCommandBus;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class PostCheepController extends AbstractController
{
    #[Route("/cheeps", name: "post_cheep")]
    public function __invoke(Request $request): Response
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
            new DoctrineOrmCheeps($entityManager)
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

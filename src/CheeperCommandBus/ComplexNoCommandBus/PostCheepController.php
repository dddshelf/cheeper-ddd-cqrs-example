<?php

declare(strict_types=1);

namespace CheeperCommandBus\ComplexNoCommandBus;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use function Safe\sprintf;

final class PostCheepController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        //snippet complex-command-handler-execution
        //ignore
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
        //end-ignore

        $connection = \Doctrine\DBAL\DriverManager::getConnection([/** ... */]);
        $entityManager = \Doctrine\ORM\EntityManager::create(
            $connection,
            new \Doctrine\ORM\Configuration()
        );

        $postCheepHandler = new PostCheepHandler(
            new DoctrineOrmAuthors($entityManager),
            new DoctrineOrmCheeps($entityManager)
        );

        $logger = new \Monolog\Logger(
            'dispatched-commands',
            [new StreamHandler('/var/logs/app/commands.log')]
        );

        try {
            $logger->info('Executing SignUp command');
            $entityManager->transactional(function() use($command, $postCheepHandler, $logger) {
                ($postCheepHandler)($command);
                $logger->info('SignUp command executed successfully');
            });
        } catch (AuthorDoesNotExist | InvalidArgumentException $exception) {
            $logger->error('SignUp command failed');
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
        //end-snippet

        return new Response('', Response::HTTP_CREATED);
    }
}

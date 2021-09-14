<?php

declare(strict_types=1);

namespace CheeperCommandHandlers;

use Cheeper\Application\Command\Cheep\UpdateCheepMessage;
use Cheeper\Application\Command\Cheep\UpdateCheepMessageHandler;
use Cheeper\Infrastructure\Persistence\DoctrineOrmCheeps;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UpdateCheepMessageController extends AbstractController
{
    #[Route("/api/cheep", methods: ["PUT"])]
    public function __invoke(Request $request): Response
    {
        // Decode the request

        /**
         * @var array{cheeper_id: string, message: string}
         */
        $data = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        //snippet recompose-cheep-controller
        // Dependencies
        $connection = \Doctrine\DBAL\DriverManager::getConnection([/** connection params */]);
        $entityManager = EntityManager::create($connection, new \Doctrine\ORM\Configuration());
        $cheeps = new DoctrineOrmCheeps($entityManager);

        // Create command
        $command = new UpdateCheepMessage(
            $data['cheeper_id'],
            $data['message']
        );

        // Instanciate the command handler
        $updateCheepMessageHandler = new UpdateCheepMessageHandler($cheeps);

        // Run the command
        $updateCheepMessageHandler->__invoke($command);
        //end-snippet

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}

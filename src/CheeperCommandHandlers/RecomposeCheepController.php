<?php

declare(strict_types=1);

namespace CheeperCommandHandlers;

use Cheeper\Application\Command\Cheep\RecomposeCheep;
use Cheeper\Application\Command\Cheep\RecomposeCheepHandler;
use Cheeper\DomainModel\Cheep\Cheeps;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Safe\json_decode;

final class RecomposeCheepController extends AbstractController
{
    //snippet recompose-cheep-controller
    /** @Route("/api/cheep", methods={"PUT"}) */
    public function __invoke(Request $request, Cheeps $cheeps): Response
    {
        // Decode the request
        $data = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        // Create command
        $command = new RecomposeCheep(
            $data['cheeper_id'],
            $data['message']
        );

        // Instanciate the command handler
        $recomposeCheepHandler = new RecomposeCheepHandler($cheeps);

        // Run the command
        $recomposeCheepHandler->__invoke($command);

        return new JsonResponse([], Response::HTTP_CREATED);
    }
    //end-snippet
}

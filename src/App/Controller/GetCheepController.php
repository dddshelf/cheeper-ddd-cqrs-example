<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CheepDto;
use Cheeper\Application\CheepApplicationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetCheepController extends AbstractController
{
    use ProtectsInvaritans;

    public function __construct(
        private readonly CheepApplicationService $cheepApplicationService
    ) {
    }

    #[Route("/cheeps/{id}", methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Retrieves a single cheep by ID",
        content: new OA\JsonContent(
            ref: new Model(type: CheepDto::class)
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: "When cheep does not exist given the ID."
    )]
    public function __invoke(string $id): Response
    {
        $this->assertNotEmptyString($id, "Cheep ID");

        $cheep = $this->cheepApplicationService->getCheep($id);

        if (null === $cheep) {
            throw $this->createNotFoundException("Cheep with ID $id was not found.");
        }

        return $this->json(
            CheepDto::assembleFrom($cheep)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AuthorDto;
use App\Dto\CheepDto;
use Cheeper\Application\CheepApplicationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

final class GetCheepController extends AbstractController
{
    public function __construct(
        private readonly CheepApplicationService $cheepApplicationService
    ) {
    }

    #[Route("/cheeps/{id}", methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Retrieves a single cheep by ID",
        content: new OA\JsonContent(
            oneOf: [
                new OA\Schema(ref: new Model(type: CheepDto::class),)
            ]
        )
    )]
    public function __invoke(string $id): Response
    {
        return $this->json(
            CheepDto::assembleFrom(
                $this->cheepApplicationService->getCheep($id)
            )
        );
    }
}
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AuthorDto;
use Cheeper\Application\AuthorApplicationService;
use Cheeper\DomainModel\Author\Author;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetAuthorsController extends AbstractController
{
    public function __construct(
        private readonly AuthorApplicationService $authorApplicationService,
    ) {
    }

    #[Route("/authors", methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Retrieves the full list of authors",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                ref: new Model(type: AuthorDto::class)
            )
        )
    )]
    public function __invoke(): Response
    {
        $authors = $this->authorApplicationService->getAuthors();

        return new JsonResponse(
            array_map(
                static fn (Author $a) => AuthorDto::assembleFrom($a),
                $authors
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CheepDto;
use Cheeper\Application\CheepApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Cheep\Cheep;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetTimelineController extends AbstractController
{
    use ProtectsInvaritans;

    private const DEFAULT_TIMELINE_CHUNK_SIZE = 10;

    public function __construct(
        private readonly CheepApplicationService $cheepApplicationService
    ) {
    }

    #[Route("/authors/{id}/timeline", methods: [Request::METHOD_GET])]
    #[OA\Parameter(
        name: "offset",
        description: "The offset position where to start getting cheeps from",
        in: "query",
        required: false,
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Parameter(
        name: "size",
        description: "The total number of cheeps to get",
        in: "query",
        required: false,
        schema: new OA\Schema(type: "int")
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Retrieves author timeline",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                ref: new Model(type: CheepDto::class)
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "When the author ID is not valid"
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: "When the author does not exist"
    )]
    public function __invoke(string $id, Request $request): Response
    {
        $this->assertNotEmptyString($id, "Author ID");
        $this->assertValidUuid($id);

        $offset     = $request->query->getInt('offset');
        $size       = $request->query->getInt('size', self::DEFAULT_TIMELINE_CHUNK_SIZE);

        try {
            $timeline = $this->cheepApplicationService->timelineFrom($id, $offset, $size);
        } catch (AuthorDoesNotExist) {
            throw $this->createNotFoundException();
        }

        $cheeps = array_map(
            static fn (Cheep $c) => CheepDto::assembleFrom($c),
            $timeline
        );

        return $this->json($cheeps);
    }
}

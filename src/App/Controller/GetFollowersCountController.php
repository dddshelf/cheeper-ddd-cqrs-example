<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\CountFollowersQuery;
use Cheeper\Application\CountFollowersQueryHandler;
use Cheeper\Application\FollowApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetFollowersCountController extends AbstractController
{
    use ProtectsInvaritans;

    public function __construct(
        private readonly FollowApplicationService $followApplicationService,
        private readonly CountFollowersQueryHandler $countFollowersQueryHandler,
    ) {
    }

    #[Route(path: "/authors/{authorId}/followers/total", methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: "Retrieves the total number of followers for a given author",
        content: new OA\JsonContent(
            oneOf: [
                new OA\Schema(
                    properties: [
                        new OA\Property(property: "count", type: "int"),
                    ]
                ),
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "When the authorId is not a valid."
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: "When the given author does not exist"
    )]
    public function __invoke(string $authorId): Response
    {
        $this->assertNotEmptyString($authorId, "Author ID");
        $this->assertValidUuid($authorId);

        try {
            $count = ($this->countFollowersQueryHandler)(
                new CountFollowersQuery($authorId)
            );
        } catch (AuthorDoesNotExist $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->json([
            'count' => $count,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\FollowApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;
use Psl\Json;
use Psl\Type;

final class PostFollowersController extends AbstractController
{
    public function __construct(
        private readonly FollowApplicationService $followApplicationService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route("/followers", methods: [Request::METHOD_POST])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "from_author_id", type: "string", nullable: false),
                new OA\Property(property: "to_author_id", type: "string", nullable: false),
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: "Creates a new follower",
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: "When the data submitted is not valid",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "type", type: "string"),
                new OA\Property(property: "title", type: "string"),
                new OA\Property(property: "detail", type: "string"),
                new OA\Property(
                    property: "violations",
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "propertyPath", type: "string"),
                            new OA\Property(property: "title", type: "string"),
                            new OA\Property(
                                property: "parameters",
                                properties: [
                                    new OA\Property(property: "{{ field }}", type: "string", nullable: true),
                                    new OA\Property(property: "{{ value }}", type: "string", nullable: true),
                                ],
                                type: "object"
                            ),
                            new OA\Property(property: "type", type: "string")
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: "When one of the authors don't exist"
    )]
    public function __invoke(Request $request): Response
    {
        $constraints = new Assert\Collection([
            'from_author_id' => [new Assert\NotBlank(), new Assert\Uuid()],
            'to_author_id' => [new Assert\NotBlank(), new Assert\Uuid()],
        ]);

        $data = Json\typed($request->getContent(), Type\dict(Type\string(), Type\string()));
        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            return $this->json($violations, status: Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->followApplicationService->followTo($data['from_author_id'], $data['to_author_id']);
        } catch (AuthorDoesNotExist $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return new Response(status: Response::HTTP_CREATED);
    }
}
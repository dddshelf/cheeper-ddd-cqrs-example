<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\CheepDto;
use Cheeper\Application\CheepApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Psl\Json;
use Psl\Type;
use OpenApi\Attributes as OA;

final class PostCheepController extends AbstractController
{
    public function __construct(
        private readonly CheepApplicationService $cheepService,
        private readonly ValidatorInterface      $validator,
    ) {
    }

    #[Route("/cheeps", methods: [Request::METHOD_POST])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "username", type: "string", nullable: false),
                new OA\Property(property: "message", type: "string", nullable: false),
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: "Creates a new cheep",
        content: new OA\JsonContent(
            ref: new Model(type: CheepDto::class)
        )
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
        description: "When the author does not exist"
    )]
    public function __invoke(Request $request): Response
    {
        $constraint = new Assert\Collection([
            'username' => new Assert\NotBlank(),
            'message' => new Assert\NotBlank()
        ]);

        $data = Json\typed($request->getContent(), Type\dict(Type\string(), Type\string()));

        $violations = $this->validator->validate(
            $data,
            $constraint
        );

        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_BAD_REQUEST);
        }

        try {
            $cheep = $this->cheepService->postCheep(
                $data['username'],
                $data['message'],
            );
        } catch (AuthorDoesNotExist) {
            throw $this->createNotFoundException();
        }

        return new JsonResponse(
            data: CheepDto::assembleFrom($cheep),
            status: Response::HTTP_CREATED,
        );
    }
}

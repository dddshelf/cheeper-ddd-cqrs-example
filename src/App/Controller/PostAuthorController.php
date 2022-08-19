<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AuthorDto;
use Cheeper\Application\AuthorApplicationService;
use Cheeper\DomainModel\Author\AuthorAlreadyExists;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Psl\Type;
use Psl\Json;
use OpenApi\Attributes as OA;

final class PostAuthorController extends AbstractController
{
    public function __construct(
        private readonly AuthorApplicationService $authorApplicationService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route("/authors", methods: [Request::METHOD_POST])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "username", type: "string", nullable: false),
                new OA\Property(property: "email", type: "string", nullable: false),
                new OA\Property(property: "name", type: "string", nullable: true),
                new OA\Property(property: "biography", type: "string", nullable: true),
                new OA\Property(property: "location", type: "string", nullable: true),
                new OA\Property(property: "website", type: "string", nullable: true),
                new OA\Property(property: "birth_date", type: "string", nullable: true),
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: "Creates a new author",
        content: new OA\JsonContent(
            ref: new Model(type: AuthorDto::class)
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
        response: Response::HTTP_CONFLICT,
        description: "When the author is already registered with the given username"
    )]
    public function __invoke(Request $request): Response
    {
        $constraint = new Assert\Collection([
            'username' => [new Assert\NotBlank()],
            'email' => [new Assert\NotBlank(), new Assert\Email()],
            'name' => new Assert\Optional(),
            'biography' => new Assert\Optional(),
            'location' => new Assert\Optional(),
            'website' => new Assert\Optional(new Assert\Url()),
            'birth_date' => new Assert\Optional(new Assert\DateTime(format: "Y-m-d"))
        ]);

        $data = Json\typed($request->getContent(), Type\dict(Type\string(), Type\string()));
        $violations = $this->validator->validate($data, $constraint);

        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_BAD_REQUEST);
        }

        try {
            $author = $this->authorApplicationService->signUp(
                Uuid::uuid4()->toString(),
                $data['username'],
                $data['email'],
                $data['name'],
                $data['biography'],
                $data['location'],
                $data['website'],
                $data['birth_date']
            );
        } catch (AuthorAlreadyExists) {
            throw new HttpException(statusCode: Response::HTTP_CONFLICT);
        }

        return $this->json(
            data: AuthorDto::assembleFrom($author),
            status: Response::HTTP_CREATED
        );
    }
}
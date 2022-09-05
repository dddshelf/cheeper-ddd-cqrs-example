<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\CommandBus;
use Cheeper\Application\PostCheep\PostCheepCommand;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use OpenApi\Attributes as OA;
use Psl\Json;
use Psl\Type;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PostCheepController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
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
        headers: [
            new OA\Header(
                header: "Location",
                description: "The URI where the new cheep can be fetched from",
            ),
        ]
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
                            new OA\Property(property: "type", type: "string"),
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
            'message' => new Assert\NotBlank(),
        ]);

        $data = Json\typed(
            $request->getContent(),
            Type\shape([
                'username' => Type\non_empty_string(),
                'message' => Type\non_empty_string(),
            ])
        );

        $violations = $this->validator->validate(
            $data,
            $constraint
        );

        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_BAD_REQUEST);
        }

        $cheepId = Uuid::uuid6();

        try {
            $this->commandBus->handle(
                new PostCheepCommand(
                    $cheepId->toString(),
                    $data['username'],
                    $data['message'],
                )
            );
        } catch (AuthorDoesNotExist) {
            throw $this->createNotFoundException();
        }

        return new Response(
            status: Response::HTTP_CREATED,
            headers: [
                "Location" => $this->urlGenerator->generate("get_cheep", ['id' => $cheepId->toString()], UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AuthorDto;
use Cheeper\Application\AuthorApplicationService;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use function Safe\json_decode;

final class PostAuthorController extends AbstractController
{
    public function __construct(
        private readonly AuthorApplicationService $authorApplicationService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route("/authors", methods: [Request::METHOD_POST])]
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

        $data = json_decode($request->getContent(), true);
        $violations = $this->validator->validate($data, $constraint);

        if (count($violations) > 0) {
            return $this->json($violations, Response::HTTP_BAD_REQUEST);
        }

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

        return $this->json(
            data: AuthorDto::assembleFrom($author),
            status: Response::HTTP_CREATED
        );
    }
}
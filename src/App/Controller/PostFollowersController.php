<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\FollowApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Safe\json_decode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

final class PostFollowersController extends AbstractController
{
    public function __construct(
        private readonly FollowApplicationService $followApplicationService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route("/followers", methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): Response
    {
        $constraints = new Assert\Collection([
            'from_author_id' => [new Assert\NotBlank(), new Assert\Uuid()],
            'to_author_id' => [new Assert\NotBlank(), new Assert\Uuid()],
        ]);

        $data = json_decode($request->getContent(), true);
        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            return $this->json($violations, status: Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->followApplicationService->followTo($data['from_author_id'], $data['to_author_id']);
        } catch (AuthorDoesNotExist $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return new Response(status: Response::HTTP_OK);
    }
}
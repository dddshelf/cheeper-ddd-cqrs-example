<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\CheepDto;
use Cheeper\Application\CheepApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use function Safe\json_decode;

final class PostCheepController extends AbstractController
{
    public function __construct(
        private readonly CheepApplicationService $cheepService,
        private readonly ValidatorInterface      $validator,
    ) {
    }

    #[Route("/cheeps", methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): Response
    {
        $constraint = new Assert\Collection([
            'username' => new Assert\NotBlank(),
            'message' => new Assert\NotBlank()
        ]);

        $data = json_decode($request->getContent(), true);

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
        } catch (\Exception) {
            throw new HttpException(statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(
            data: CheepDto::assembleFrom($cheep),
            status: Response::HTTP_CREATED,
        );
    }
}

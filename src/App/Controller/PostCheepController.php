<?php
declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\CheepApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $violations = $this->validator->validate(
            json_decode($request->getContent()),
            $constraint
        );

        if (count($violations) > 0) {
            throw new HttpException(statusCode: Response::HTTP_BAD_REQUEST, message: "Invalid input data!");
        }

        try {
            $this->cheepService->postCheep(
                $request->request->get('username'),
                $request->request->get('message')
            );
        } catch (\Exception) {
            throw new HttpException(statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(status: Response::HTTP_OK);
    }
}

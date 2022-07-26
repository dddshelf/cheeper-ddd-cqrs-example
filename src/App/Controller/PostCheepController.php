<?php
declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\CheepService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PostCheepController extends AbstractController
{
    public function __construct(
        private readonly CheepService $cheepService,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route("/cheeps", methods: [Request::METHOD_POST])]
    public function postAction(Request $request): Response
    {
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

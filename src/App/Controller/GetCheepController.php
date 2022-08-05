<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CheepDto;
use Cheeper\Application\CheepApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetCheepController extends AbstractController
{
    public function __construct(
        private readonly CheepApplicationService $cheepApplicationService
    ) {
    }

    #[Route("/cheeps/{id}", methods: [Request::METHOD_GET])]
    public function __invoke(string $id): Response
    {
        return $this->json(
            CheepDto::assembleFrom(
                $this->cheepApplicationService->getCheep($id)
            )
        );
    }
}
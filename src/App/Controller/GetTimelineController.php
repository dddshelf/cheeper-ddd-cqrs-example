<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\CheepApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetTimelineController extends AbstractController
{
    private const DEFAULT_TIMELINE_CHUNK_SIZE = 10;

    public function __construct(
        private readonly CheepApplicationService $cheepApplicationService
    ) {
    }

    #[Route("/authors/{id}/timeline", methods: [Request::METHOD_GET])]
    public function __invoke(string $id, Request $request): Response
    {
        $offset     = $request->query->getInt('offset');
        $size       = $request->query->getInt('size', self::DEFAULT_TIMELINE_CHUNK_SIZE);

        $cheeps = $this->cheepApplicationService->timelineFrom($id, $offset, $size);

        return $this->json($cheeps);
    }
}
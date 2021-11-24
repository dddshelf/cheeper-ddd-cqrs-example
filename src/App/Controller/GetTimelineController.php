<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\Query\Timeline\Timeline;
use Cheeper\Chapter6\Application\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetTimelineController extends AbstractController
{
    private const DEFAULT_TIMELINE_CHUNK_SIZE = 10;

    #[Route(path: "/api/timelines/{authorId}", methods: ["GET"])]
    public function __invoke(string $id, QueryBus $queryBus, SerializerInterface $serializer, Request $request): Response
    {
        $offset = $request->query->getInt('offset');
        $size = $request->query->getInt('size', self::DEFAULT_TIMELINE_CHUNK_SIZE);

        $timeline = $queryBus->query(
            Timeline::fromArray([
                'authorId' => $id,
                'offset' => $offset,
                'size' => $size
            ])
        );

        return new JsonResponse(
            $serializer->serialize($timeline, 'json'),
            json: true
        );
    }
}
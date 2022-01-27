<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use Cheeper\Chapter5\Application\Query\QueryBus;
use Cheeper\Chapter7\Application\Query\Timeline\TimelineQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

//snippet timeline-controller
final class GetTimelineController extends AbstractController
{
    private const DEFAULT_TIMELINE_CHUNK_SIZE = 10;

    #[Route(path: "/chapter7/author/{authorId}/timeline", methods: ["GET"])]
    public function __invoke(string $authorId, QueryBus $queryBus, SerializerInterface $serializer, Request $request): Response
    {
        $offset = $request->query->getInt('offset');
        $size = $request->query->getInt('size', self::DEFAULT_TIMELINE_CHUNK_SIZE);

        $timeline = $queryBus->query(
            TimelineQuery::fromArray([
                'author_id' => $authorId,
                'offset' => $offset,
                'size' => $size,
            ])
        );

        // SymfonyMessengerCheepProjectionToRedis

        return new JsonResponse(
            $serializer->serialize($timeline, 'json'),
            json: true
        );
    }
}
//end-snippet

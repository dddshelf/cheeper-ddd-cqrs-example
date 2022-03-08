<?php

declare(strict_types=1);

namespace App\Controller\Chapter8;

use Cheeper\Chapter7\Application\Author\Query\TimelineQuery;
use Cheeper\Chapter7\Application\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

//snippet chapter8-timeline-controller
final class GetTimelineController extends AbstractController
{
    private const DEFAULT_TIMELINE_CHUNK_SIZE = 10;

    #[Route(path: "/chapter8/author/{authorId}/timeline", methods: Request::METHOD_GET)]
    public function __invoke(
        string $authorId,
        QueryBus $queryBus,
        SerializerInterface $serializer,
        Request $request
    ): Response {
        $offset = $request->query->getInt('offset');
        $size = $request->query->getInt('size', self::DEFAULT_TIMELINE_CHUNK_SIZE);

        $timeline = $queryBus->query(
            TimelineQuery::fromArray([
                'author_id' => $authorId,
                'offset' => $offset,
                'size' => $size,
            ])
        );

        return $this->json($timeline);
    }
}
//end-snippet

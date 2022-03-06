<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter7\Application\Author\Query\CountFollowersQuery;
use Cheeper\Chapter7\Application\Author\Query\TimelineQuery;
use Cheeper\Chapter7\Application\QueryBus;
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

        $httpCode = Response::HTTP_ACCEPTED;
        $httpContent = [
            '_meta' => [],
            'data' => [],
        ];

        try {
            $query = TimelineQuery::fromArray([
                'author_id' => $authorId,
                'offset' => $offset,
                'size' => $size,
            ]);
            $timeline = $queryBus->query($query);

            $httpContent['_meta']['message_id'] = $query->messageId()?->toString();
            $httpContent['data'] = $timeline;
        } catch (AuthorDoesNotExist $e) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $httpContent['data'] = $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpContent['data'] = $e->getMessage();
        }

        return $this->json(
            $httpContent,
            $httpCode
        );
    }
}
//end-snippet

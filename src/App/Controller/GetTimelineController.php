<?php

declare(strict_types=1);

namespace App\Controller;

use Cheeper\Application\Query\Timeline\Timeline;
use Cheeper\Chapter6\Application\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetTimelineController extends AbstractController
{
    #[Route(path: "/api/timelines/{id}", methods: ["GET"])]
    public function __invoke(string $id, QueryBus $queryBus, SerializerInterface $serializer): Response
    {
        $timeline = $queryBus->query(
            Timeline::fromArray(['authorId' => $id])
        );

        return new JsonResponse(
            $serializer->serialize($timeline, 'json'),
            json: true
        );
    }
}
<?php

declare(strict_types=1);

namespace App\Controller\Chapter7;

use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter6\Application\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

//snippet follow-counter-controller
final class FollowCounterController extends AbstractController
{
    #[Route(path: "/chapter7/api/{authorId}/follow-counters", methods: ["GET"])]
    public function __invoke(string $authorId, QueryBus $queryBus, SerializerInterface $serializer, Request $request): Response
    {
        $timeline = $queryBus->query(
            CountFollowers::ofAuthor($authorId)
        );

        return new JsonResponse(
            $serializer->serialize($timeline, 'json'),
            json: true
        );
    }
}
//end-snippet
<?php

declare(strict_types=1);

namespace App\Controller\Chapter5\CheeperGetFollowersWithQueryBus;

use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\DomainModel\Follow\FollowersCounterResource;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class GetFollowersCounterController extends AbstractController
{
    public function __construct(
        private QueryBus $queryBus
    )
    {
    }

    #[Route("/chapter-5/api/followers-counter/using-query-bus/{userId}", methods: ["GET"])]
    public function __invoke(string $userId): JsonResponse
    {
        $authorId = AuthorId::fromString($userId);

        try {
            return new JsonResponse(
                $this->queryBus->query(
                    CountFollowers::ofAuthor($authorId)
                ),
                Response::HTTP_OK
            );
        } catch (AuthorDoesNotExist $e) {
            $jsonResponse = new JsonResponse($e, Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $jsonResponse = new JsonResponse($e, Response::HTTP_NOT_FOUND);
        }

        return $jsonResponse;
    }
}
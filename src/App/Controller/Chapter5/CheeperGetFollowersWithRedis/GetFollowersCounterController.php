<?php

declare(strict_types=1);

namespace App\Controller\Chapter5\CheeperGetFollowersWithRedis;

use Cheeper\Chapter5\DomainModel\Follow\FollowersCounterResource;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Follow\Follows;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GetFollowersCounterController extends AbstractController
{
    public function __construct(
        private Follows $follows,
        private Authors $authors
    )
    {
    }

    #[Route("/chapter-5/api/followers-counter/using-redis/{userId}", methods: ["GET"])]
    public function __invoke(string $userId): JsonResponse
    {
        $authorId = AuthorId::fromString($userId);
        $author = $this->authors->ofId($authorId);

        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $numberOfFollowers = $this->follows->numberOfFollowersFor($authorId);

        return $this->json(
            new FollowersCounterResource(
                userId: $author->userId()->id(),
                userName: $author->userName()->userName(),
                counter: $numberOfFollowers
            )
        );
    }
}

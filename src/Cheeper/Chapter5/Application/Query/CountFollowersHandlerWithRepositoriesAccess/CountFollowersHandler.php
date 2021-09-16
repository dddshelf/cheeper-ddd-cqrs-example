<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithRepositoriesAccess;

use Architecture\CQRS\App\Repository\FollowersRepository;
use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;

//snippet count-followers-handler
final class CountFollowersHandler
{
    public function __construct(
        private FollowersRepository $followersRepository,
        private Authors $authorsRepository
    ) {
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromString($query->authorId());

        $author = $this->authorsRepository->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $followersCount = $this->followersRepository->ofAuthorId($authorId)?->followers() ?? 0;

        // Other option would be with a counter method in the Repository
        // $followersCount = $this->followersRepository->countOfAuthorId($authorId));

        return new CountFollowersResponse(
            authorId: $authorId->toString(),
            authorUsername: $author->userName()->userName(),
            numberOfFollowers: $followersCount
        );
    }
}
//end-snippet

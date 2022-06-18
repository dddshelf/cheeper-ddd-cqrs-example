<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRepositoriesAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter4\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter4\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;

//snippet count-followers-handler
final class CountFollowersQueryHandler
{
    public function __construct(
        private FollowRepository $followRepository,
        private AuthorRepository $authorRepository
    ) {
    }

    public function __invoke(CountFollowersQuery $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromString($query->authorId());

        $author = $this->authorRepository->ofId($authorId);
        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $followersCount = count($this->followRepository->toAuthorId($authorId));

        // Another option would be with a counter method in the Repository
        // $followersCount = $this->followers->countOfAuthorId($authorId));

        return new CountFollowersResponse(
            authorId: $authorId->toString(),
            authorUsername: $author->userName()->userName(),
            numberOfFollowers: $followersCount
        );
    }
}

//end-snippet

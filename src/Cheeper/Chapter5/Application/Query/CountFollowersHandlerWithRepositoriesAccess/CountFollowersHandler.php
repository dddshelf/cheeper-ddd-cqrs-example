<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithRepositoriesAccess;

use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Cheeper\Chapter5\DomainModel\Follow\Followers;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;

//snippet count-followers-handler
final class CountFollowersHandler
{
    public function __construct(
        private Followers $followers,
        private Authors   $authors
    )
    {
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromString($query->authorId());

        $author = $this->authors->ofId($authorId);

        if (null === $author) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $followersCount = $this->followers->ofAuthorId($authorId)?->followers() ?? 0;

        // Other option would be with a counter method in the Repository
        // $followersCount = $this->followers->countOfAuthorId($authorId));

        return new CountFollowersResponse(
            authorId: $authorId->toString(),
            authorUsername: $author->userName()->userName(),
            numberOfFollowers: $followersCount
        );
    }
}
//end-snippet

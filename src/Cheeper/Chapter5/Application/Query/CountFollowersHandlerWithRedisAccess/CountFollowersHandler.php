<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithRedisAccess;

use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Predis\ClientInterface as Redis;

//snippet count-followers-handler
final class CountFollowersHandler
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromString($query->authorId());
        $result = $this->redis->get(
            'author_followers_counter_projection:'.$authorId->toString()
        );

        if (null === $result) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return new CountFollowersResponse(
            authorId: $result['id'],
            authorUsername: $result['username'],
            numberOfFollowers: $result['followers']
        );
    }
}
//end-snippet

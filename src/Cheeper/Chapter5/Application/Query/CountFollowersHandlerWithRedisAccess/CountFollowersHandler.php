<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithRedisAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Redis;

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

        $data = $this->redis->get(
            'author_followers_counter_projection:'.$authorId->toString()
        );

        if (false === $data) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $result = json_decode($data, true, flags: JSON_THROW_ON_ERROR);

        return new CountFollowersResponse(
            authorId: $result['id'],
            authorUsername: $result['username'],
            numberOfFollowers: (int)$result['followers']
        );
    }
}
//end-snippet

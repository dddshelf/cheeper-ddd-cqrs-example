<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRedisAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Redis;

//snippet count-followers-handler
final class CountFollowersQueryHandler
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CountFollowersQuery $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromString($query->authorId());

        $result = $this->redis->hGetAll(
            'author_followers_counter_projection:'.$authorId->toString()
        );

        if (empty($result)) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return new CountFollowersResponse(
            authorId: $result['id'],
            authorUsername: $result['username'],
            numberOfFollowers: (int)$result['followers']
        );
    }
}
//end-snippet

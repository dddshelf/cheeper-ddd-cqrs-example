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

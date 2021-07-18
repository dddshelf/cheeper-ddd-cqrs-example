<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithRedisAccess;

use Architecture\CQRS\Application\Query\CountFollowersResponse;
use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\DomainModel\Author\AuthorId;
use Predis\Client as Redis;

//snippet count-followers-handler
final class CountFollowersHandler
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromUuid($query->authorId());
        $result = $this->redis->get(
            'author_followers_counter_projection:'.$authorId->toString()
        );

        return new CountFollowersResponse(
            authorId: $result['id'],
            authorUsername: $result['username'],
            numberOfFollowers: $result['followers']
        );
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Query;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandler;
use Redis;

//snippet count-followers-handler
final class CountFollowersQueryHandler
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CountFollowersQuery $query): CountFollowersQueryResponse
    {
        $authorId = AuthorId::fromString($query->authorId());

        $result = $this->redis->hGetAll(
            sprintf(
                CreateFollowersCounterProjectionHandler::REDIS_KEY_TEMPLATE,
                $authorId->toString()
            )
        );

        if (empty($result)) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return new CountFollowersQueryResponse(
            authorId: $result['id'],
            authorUsername: $result['username'],
            numberOfFollowers: (int)$result['followers']
        );
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Redis;

//snippet create-followers-counter-projection-projector
final class CreateFollowersCounterProjectionHandler implements CreateFollowersCounterProjectionHandlerInterface
{
    public const REDIS_KEY_TEMPLATE = 'author_followers_counter_projection:%s';

    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CreateFollowersCounterProjection $projection): void
    {
        $authorId = AuthorId::fromString($projection->authorId());

        $result = [
            'id' => $projection->authorId(),
            'username' => $projection->authorUsername(),
            'followers' => 0,
        ];

        $this->redis->set(
            sprintf(
                self::REDIS_KEY_TEMPLATE,
                $authorId->toString()
            ),
            json_encode($result)
        );
    }
}
//end-snippet

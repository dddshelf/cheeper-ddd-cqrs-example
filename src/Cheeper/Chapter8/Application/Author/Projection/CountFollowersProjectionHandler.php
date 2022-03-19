<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Author\Projection;

use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandler;
use Redis;

//snippet projector-count-followers
final class CountFollowersProjectionHandler
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CountFollowersProjection $projection): void
    {
        $this->redis->hIncrBy(
            sprintf(
                CreateFollowersCounterProjectionHandler::REDIS_KEY_TEMPLATE,
                $projection->authorId()
            ),
            'followers',
            1
        );
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

use Redis;

//snippet snippet
final class IncrementCountFollowersProjectionHandler
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(IncrementCountFollowersProjection $projection): void
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

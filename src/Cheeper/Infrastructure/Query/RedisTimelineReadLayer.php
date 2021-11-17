<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Query;

use Cheeper\Application\Query\Timeline\TimelineReadLayer;
use Redis;

final class RedisTimelineReadLayer implements TimelineReadLayer
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function byAuthorId(string $authorId, int $offset = 0, int $size = 10): array
    {
        $serializedCheeps = $this->redis->lRange(
            'timelines_' . $authorId,
            $offset,
            $size
        );

        return array_map(
            static fn(string $cheep): array => unserialize($cheep),
            $serializedCheeps
        );
    }
}
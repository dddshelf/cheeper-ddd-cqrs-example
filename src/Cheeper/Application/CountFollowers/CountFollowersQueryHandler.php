<?php

declare(strict_types=1);

namespace Cheeper\Application\CountFollowers;

final class CountFollowersQueryHandler
{
    public function __construct(
        private readonly \Redis $redis,
    ) {
    }

    public function __invoke(CountFollowersQuery $query): int
    {
        $totalFollowers = $this->redis->get("followers_of:" . $query->authorId);

        return false === $totalFollowers ? 0 : (int)$totalFollowers;
    }
}

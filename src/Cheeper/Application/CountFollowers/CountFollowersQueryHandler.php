<?php

declare(strict_types=1);

namespace Cheeper\Application\CountFollowers;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CountFollowersQueryHandler
{
    public function __construct(
        private readonly \Redis $redis,
    ) {
    }

    public function __invoke(CountFollowersQuery $query): CountFollowersQueryResponse
    {
        $totalFollowers = $this->redis->get("followers_of:" . $query->authorId);

        if (false !== $totalFollowers) {
            $totalFollowers = (int)$totalFollowers;
        }

        return new CountFollowersQueryResponse(
            false === $totalFollowers ? 0 : $totalFollowers
        );
    }
}

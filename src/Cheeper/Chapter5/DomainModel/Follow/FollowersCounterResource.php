<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\DomainModel\Follow;

final class FollowersCounterResource
{
    public function __construct(
        public string $userId,
        public string $userName,
        public int $counter,
    ) {
    }
}

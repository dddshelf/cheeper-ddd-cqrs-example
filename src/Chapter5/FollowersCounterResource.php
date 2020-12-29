<?php

declare(strict_types=1);

namespace Chapter5;

final class FollowersCounterResource
{
    public function __construct(
        public string $userId,
        public string $userName,
        public int $counter,
    )
    { }
}

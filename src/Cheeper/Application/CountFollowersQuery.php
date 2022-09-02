<?php

declare(strict_types=1);

namespace Cheeper\Application;

/** @psalm-immutable */
final class CountFollowersQuery implements Query
{
    public function __construct(
        public readonly string $authorId
    ) {
    }
}
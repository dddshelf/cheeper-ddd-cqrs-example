<?php

declare(strict_types=1);

namespace Cheeper\Application\CountFollowers;

use Cheeper\Application\Query;

/** @psalm-immutable */
final class CountFollowersQuery implements Query
{
    /** @psalm-param non-empty-string $authorId */
    public function __construct(
        public readonly string $authorId
    ) {
    }
}
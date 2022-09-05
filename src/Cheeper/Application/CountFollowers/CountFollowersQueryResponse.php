<?php

declare(strict_types=1);

namespace Cheeper\Application\CountFollowers;

use Cheeper\Application\QueryResponse;

/** @psalm-immutable */
final class CountFollowersQueryResponse implements QueryResponse
{
    public function __construct(
        public readonly int $totalNumberOfFollowers
    ) {
    }
}

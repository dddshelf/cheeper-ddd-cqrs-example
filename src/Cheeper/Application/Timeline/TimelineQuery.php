<?php

declare(strict_types=1);

namespace Cheeper\Application\Timeline;

use Cheeper\Application\Query;

/** @psalm-immutable */
final class TimelineQuery implements Query
{
    /**
     * @psalm-param non-empty-string $authorId
     * @psalm-param positive-int|0 $offset
     * @psalm-param positive-int $size
     */
    public function __construct(
        public readonly string $authorId,
        public readonly int $offset,
        public readonly int $size,
    ) {
    }
}

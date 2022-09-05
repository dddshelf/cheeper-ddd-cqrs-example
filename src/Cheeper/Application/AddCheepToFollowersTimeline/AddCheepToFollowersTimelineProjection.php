<?php

declare(strict_types=1);

namespace Cheeper\Application\AddCheepToFollowersTimeline;

use Cheeper\Application\Projection;

/** @psalm-immutable */
final class AddCheepToFollowersTimelineProjection implements Projection
{
    public function __construct(
        public readonly string             $followerId,
        public readonly string             $cheepId,
        public readonly string             $authorId,
        public readonly string             $cheepMessage,
        public readonly \DateTimeImmutable $cheepDate,
    ) {
    }
}

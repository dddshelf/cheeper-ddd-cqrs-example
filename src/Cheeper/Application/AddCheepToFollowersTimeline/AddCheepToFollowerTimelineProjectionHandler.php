<?php

declare(strict_types=1);

namespace Cheeper\Application\AddCheepToFollowersTimeline;

use Psl\Json;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AddCheepToFollowerTimelineProjectionHandler
{
    public function __construct(
        private readonly \Redis $redis,
    ) {
    }

    public function __invoke(AddCheepToFollowersTimelineProjection $projection): void
    {
        $this->redis->lPush(
            "timeline_of:" . $projection->followerId,
            Json\encode([
                'cheepId' => $projection->cheepId,
                'authorId' => $projection->authorId,
                'cheepMessage' => $projection->cheepMessage,
                'cheepDate' => $projection->cheepDate->format(\DateTimeInterface::RFC3339_EXTENDED),
            ])
        );
    }
}

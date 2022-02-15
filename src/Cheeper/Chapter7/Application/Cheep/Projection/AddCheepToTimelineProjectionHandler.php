<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Cheep\Projection;

use Redis;

//snippet add-cheep-to-timeline-projector
final class AddCheepToTimelineProjectionHandler
{
    public function __construct(
        private Redis $redis,
    ) {
    }

    public function __invoke(AddCheepToTimelineProjection $message): void
    {
        $this->redis->lPush(
            sprintf("timelines_%s", $message->authorId),
            json_encode([
                'cheep_id' => $message->cheepId,
                'cheep_message' => $message->cheepMessage,
                'cheep_date' => $message->cheepDate,
            ], JSON_THROW_ON_ERROR)
        );
    }
}
//end-snippet

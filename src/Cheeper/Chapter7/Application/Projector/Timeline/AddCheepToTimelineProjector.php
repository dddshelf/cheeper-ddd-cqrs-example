<?php
declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Projector\Timeline;

use Redis;

//snippet add-cheep-to-timeline-projector
final class AddCheepToTimelineProjector
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

<?php

namespace Cheeper\Chapter7\Application\Projector\Timeline;

//snippet put-cheep-on-redis-timeline
final class AddCheepToTimelineProjection
{
    public function __construct(
        public string $authorId,
        public string $cheepId,
        public string $cheepMessage,
        public string $cheepDate,
    ) {
    }
}
//end-snippet
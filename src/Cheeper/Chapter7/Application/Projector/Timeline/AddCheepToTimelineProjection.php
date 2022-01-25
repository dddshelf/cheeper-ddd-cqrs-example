<?php

namespace Cheeper\Chapter7\Application\Projector\Timeline;

//snippet put-cheep-on-redis-timeline
use Cheeper\Chapter7\Application\Projector\Projection;

final class AddCheepToTimelineProjection implements Projection
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
<?php

namespace Cheeper\Chapter7\Application\Projector\Timeline;

use Cheeper\Chapter7\Application\Projector\Projection;

//snippet put-cheep-on-redis-timeline
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
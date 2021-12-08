<?php

namespace Cheeper\Application\Command\Timeline;

use Cheeper\Application\Command\AsyncCommand;

//snippet put-cheep-on-redis-timeline
final class AddCheepToTimeline implements AsyncCommand
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
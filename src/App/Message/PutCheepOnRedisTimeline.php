<?php

namespace App\Message;

//snippet put-cheep-on-redis-timeline
final class PutCheepOnRedisTimeline implements AsyncMessage
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
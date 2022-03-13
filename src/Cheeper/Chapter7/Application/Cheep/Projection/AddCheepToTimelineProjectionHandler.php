<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Cheep\Projection;

use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandler;
use Redis;

//snippet add-cheep-to-timeline-projector
final class AddCheepToTimelineProjectionHandler
{
    public const REDIS_KEY_TEMPLATE = 'author_timeline_projection:%s';

    public function __construct(
        private Redis $redis,
    ) {
    }

    public function __invoke(AddCheepToTimelineProjection $message): void
    {
        $this->redis->lPush(
            $this->getRedisKey($message),
            $this->getRedisContent($message)
        );
    }

    private function getRedisKey(AddCheepToTimelineProjection $message): string
    {
        return sprintf(
            self::REDIS_KEY_TEMPLATE,
            $message->authorId
        );
    }

    private function getRedisContent(AddCheepToTimelineProjection $message): string
    {
        return json_encode([
            'cheep_id' => $message->cheepId,
            'cheep_message' => $message->cheepMessage,
            'cheep_date' => $message->cheepDate,
        ], JSON_THROW_ON_ERROR);
    }
}
//end-snippet

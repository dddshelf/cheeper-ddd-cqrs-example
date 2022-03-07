<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Redis;

final class CreateTimelineProjectionHandler implements CreateTimelineProjectionHandlerInterface
{
    public function __construct(
        private Redis $redis
    ) {
    }

    public function __invoke(CreateTimelineProjection $projection): void
    {
        $authorId = AuthorId::fromString($projection->authorId());
        $key = sprintf('author_timeline_projection:%s', $authorId);

        $result = [
            'id' => $projection->authorId(),
            'username' => $projection->authorUsername(),
            'followers' => 0,
        ];

        $this->redis->set(
            'author_followers_counter_projection:'.$authorId->toString(),
            json_encode($result)
        );
    }
}
//end-snippet

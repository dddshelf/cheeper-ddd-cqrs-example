<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Query;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;

//snippet timeline-handler
final class TimelineQueryHandler
{
    public function __construct(
        private \Redis $redis,
    ) {
    }

    public function __invoke(TimelineQuery $query): TimelineQueryResponse
    {
        $authorId = $query->authorId();
        $key = sprintf('author_timeline_projection:%s', $authorId);

        $serializedCheeps = $this->redis->lRange(
            $key,
            $query->offset(),
            ($query->offset() + $query->size()) - 1
        );

        return new TimelineQueryResponse(
            array_map(
                static fn (string $cheep): array => json_decode($cheep, true, flags: JSON_THROW_ON_ERROR),
                $serializedCheeps
            )
        );
    }
}
//end-snippet

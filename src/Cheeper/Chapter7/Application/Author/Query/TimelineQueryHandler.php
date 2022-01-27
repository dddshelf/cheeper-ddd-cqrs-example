<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Query;

//snippet timeline-handler
final class TimelineQueryHandler
{
    public function __construct(
        private \Redis $redis,
    ) {
    }

    public function __invoke(TimelineQuery $query): TimelineQueryResponse
    {
        $serializedCheeps = $this->redis->lRange(
            sprintf('timelines_%s', $query->authorId()),
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

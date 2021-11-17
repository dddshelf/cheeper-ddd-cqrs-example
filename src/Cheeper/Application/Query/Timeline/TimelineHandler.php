<?php

declare(strict_types=1);

namespace Cheeper\Application\Query\Timeline;

final class TimelineHandler
{
    public function __construct(
        private TimelineReadLayer $timelineReadLayer
    ) {
    }

    public function __invoke(Timeline $query): TimelineResponse
    {
        return new TimelineResponse(
            $this->timelineReadLayer->byAuthorId($query->authorId(), $query->offset(), $query->size())
        );
    }
}
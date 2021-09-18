<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection;

use Architecture\CQRS\Domain\DomainEvent;
use Bunny\Channel;
use Zumba\JsonSerializer\JsonSerializer;

//snippet async-projector
final class AsyncProjector
{
    public function __construct(
        private Channel $channel,
        private JsonSerializer $serializer
    ) {
    }

    /** @param DomainEvent[] $events */
    public function project(array $events): void
    {
        foreach ($events as $event) {
            $this->channel->publish(
                $this->serializer->serialize($event),
                [],
                'events'
            );
        }
    }
}
//end-snippet

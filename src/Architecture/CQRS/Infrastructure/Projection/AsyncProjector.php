<?php declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Projection;

use Architecture\CQRS\Domain\DomainEvent;
use Bunny\Channel;
use Zumba\JsonSerializer\JsonSerializer;

//snippet async-projector
class AsyncProjector
{
    private Channel $channel;
    private JsonSerializer $serializer;

    public function __construct(
        Channel $channel,
        JsonSerializer $serializer
    ) {
        $this->channel = $channel;
        $this->serializer = $serializer;
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

<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

use Architecture\ES\Domain\EventStore;
use Architecture\ES\Domain\EventStream;
use DateTimeImmutable;
use Zumba\JsonSerializer\JsonSerializer;

//snippet event-store
final class RedisEventStore implements EventStore
{
    public function __construct(
        private \Redis $redis,
        private JsonSerializer $serializer
    ) {
    }

    public function append(EventStream $eventstream): void
    {
        foreach ($eventstream as $event) {
            $data = $this->serializer->serialize($event);

            $date = (new DateTimeImmutable())->format('YmdHis');

            $this->redis->rpush(
                'events:' . $eventstream->getAggregateId(),
                [
                    $this->serializer->serialize([
                        'type' => get_class($event),
                        'created_on' => $date,
                        'data' => $data,
                    ]),
                ]
            );
        }
    }

    public function getEventsFor(string $id): EventStream
    {
        return $this->fromVersion($id, 0);
    }

    public function fromVersion(string $id, int $version): EventStream
    {
        $serializedEvents = $this->redis->lrange(
            'events:' . $id,
            $version,
            -1
        );

        $events = [];

        /** @var string $serializedEvent */
        foreach ($serializedEvents as $serializedEvent) {
            $event = (array) $this->serializer->unserialize($serializedEvent);

            $eventData = (string) $event['data'];

            /** @var T */
            $events[] = $this->serializer->unserialize($eventData);
        }

        return new EventStream($id, $events);
    }

    public function countEventsFor(string $id): int
    {
        return $this->redis->llen('events:' . $id);
    }
}
//end-snippet

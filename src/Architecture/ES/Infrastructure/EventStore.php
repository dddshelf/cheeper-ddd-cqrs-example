<?php

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\DomainEvent;

use Architecture\ES\Domain\EventStream;
use Predis\Client;
use Zumba\JsonSerializer\JsonSerializer;
use Safe\DateTimeImmutable;

/**
 * @template T of DomainEvent
 */
//snippet event-store
class EventStore
{
    /** @var Client<?string> */
    private Client $redis;
    private JsonSerializer $serializer;

    /** @param Client<?string> $redis */
    public function __construct(Client $redis, JsonSerializer $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    /** @param EventStream<T> $eventstream */
    public function append(EventStream $eventstream): void
    {
        /** @var DomainEvent $event */
        foreach ($eventstream as $event) {
            $data = $this->serializer->serialize($event);

            $date = (new DateTimeImmutable())->format('YmdHis');

            $this->redis->rpush(
                'events:' . $eventstream->getAggregateId(),
                [
                    $this->serializer->serialize([
                        'type' => get_class($event),
                        'created_on' => $date,
                        'data' => $data
                    ])
                ]
            );
        }
    }

    /** @return EventStream<T> */
    public function getEventsFor(string $id): EventStream
    {
        return $this->fromVersion($id, 0);
    }

    /** @return EventStream<T> */
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

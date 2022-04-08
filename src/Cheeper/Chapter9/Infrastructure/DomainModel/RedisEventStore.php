<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel;

use Cheeper\Chapter7\DomainModel\DomainEvent;
use Cheeper\Chapter9\DomainModel\EventStore;
use Cheeper\Chapter9\DomainModel\EventStream;
use Redis;
use Symfony\Component\Serializer\Serializer;

//snippet code
class RedisEventStore implements EventStore
{
    private Redis $redis;
    private Serializer $serializer;

    public function __construct(
        Redis $redis,
        Serializer $serializer
    )
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function append(EventStream $eventStream): void
    {
        /** @var DomainEvent */
        foreach ($eventStream as $event) {
            $data = $this->serializer->serialize($event, 'json');

            $date = (new \DateTimeImmutable())->format('YmdHis');

            $this->redis->rpush(
                'events:' . $eventStream->getAggregateId(),
                $this->serializer->serialize([
                        'type' => get_class($event),
                        'created_on' => $date,
                        'data' => $data
                    ],
            'json'
                )
            );
        }
    }

    public function getEventsFor(string $id): EventStream
    {
        return $this->fromVersion($id, 0);
    }

    public function fromVersion(string $id, int $version): EventStream
    {
        $serializedEvents = (array) $this->redis->lrange(
            'events:' . $id,
            $version,
            -1
        );

        /** @var DomainEvent[] */
        $events = [];

        /** @var string */
        foreach ($serializedEvents as $serializedEvent) {
            $event = (array) $this->serializer->deserialize($serializedEvent);

            $eventData = (string) $event['data'];

            /** @var DomainEvent */
            $events[] = $this->serializer->deserialize($eventData);
        }

        return new EventStream($id, $events);
    }

    public function countEventsFor(string $id): int
    {
        return (int) $this->redis->llen('events:' . $id);
    }
}
//end-snippet
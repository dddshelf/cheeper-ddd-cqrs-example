<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\Infrastructure\DomainModel;

use Cheeper\Chapter7\DomainModel\DomainEvent;
use Cheeper\Chapter9\DomainModel\EventStore;
use Cheeper\Chapter9\DomainModel\EventStream;
use Redis;
use Symfony\Component\Serializer\Serializer;

//snippet code
final class RedisEventStore implements EventStore
{
    public function __construct(
        private Redis $redis,
        private Serializer $serializer,
    ) {
    }

    public function append(EventStream $eventStream): void
    {
        /** @var DomainEvent */
        foreach ($eventStream as $event) {
            $serializedEvent = $this->serialize($event);

            $this->redis->rpush(
                'events:' . $eventStream->getAggregateId(),
                $this->serializer->serialize($serializedEvent, 'json')
            );
        }
    }

    public function getEventsFor(string $id): EventStream
    {
        return $this->fromVersion($id);
    }

    private function fromVersion(string $id, int $version = 0): EventStream
    {
        $serializedEvents = $this->redis->lrange(
            'events:' . $id,
            $version,
            -1
        );

        $events = [];

        foreach ($serializedEvents as $serializedEvent) {
            /** @var DomainEvent */
            $events[] = $this->deserialize($serializedEvent);
        }

        return new EventStream($id, $events);
    }

    //ignore
    private function deserialize(array $event): DomainEvent
    {
        return $this->serializer->deserialize(
            $event['data'],
            $event['type'],
            'json'
        );
    }

    private function serialize(mixed $event): array
    {
        return [
            'type' => get_class($event),
            'created_on' => (new \DateTimeImmutable())->format('YmdHis'),
            'data' => $this->serializer->serialize($event, 'json')
        ];
    }
    //end-ignore
}
//end-snippet
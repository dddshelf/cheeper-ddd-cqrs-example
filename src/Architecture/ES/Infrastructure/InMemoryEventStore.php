<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\DomainEvent;
use Architecture\ES\Domain\EventStore;
use Architecture\ES\Domain\EventStream;

final class InMemoryEventStore implements EventStore
{
    private array $events = [];

    public function append(EventStream $eventstream): void
    {
        $aggregateId = $eventstream->getAggregateId();
        if (!array_key_exists($aggregateId, $this->events)) {
            $this->events[$aggregateId] = [];
        }

        foreach ($eventstream as $event) {
            $this->events[$aggregateId][] = $event;
        }
    }

    public function getEventsFor(string $id): EventStream
    {
        return new EventStream($id, $this->events[$id] ?? []);
    }

    public function fromVersion(string $id, int $version): EventStream
    {
        return new EventStream($id, array_slice($this->events[$id] ?? [], $version));
    }

    public function countEventsFor(string $id): int
    {
        return count($this->events[$id] ?? []);
    }
}

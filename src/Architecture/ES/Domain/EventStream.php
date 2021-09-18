<?php

declare(strict_types=1);

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\DomainEvent;

final class EventStream implements \Iterator
{
    private string $aggregateId;
    /** @var DomainEvent[] */
    private array $events;

    /**
     * @param string $aggregateId
     * @param DomainEvent[] $events
     */
    public function __construct(string $aggregateId, array $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events = $events;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function rewind(): void
    {
        reset($this->events);
    }

    public function current()
    {
        return current($this->events);
    }

    public function key(): string|int|null
    {
        return key($this->events);
    }

    public function next(): void
    {
        next($this->events);
    }

    public function valid(): bool
    {
        return key($this->events) !== null;
    }
}

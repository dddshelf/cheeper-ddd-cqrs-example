<?php

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\DomainEvent;

/**
 * @template T of DomainEvent
 * @implements \Iterator<T>
 */
class EventStream implements \Iterator
{
    private string $aggregateId;
    /** @var T[] */
    private array $events;

    /**
     * @param string $aggregateId
     * @param T[] $events
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

    /** @return T */
    public function current()
    {
        return current($this->events);
    }

    public function key()
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

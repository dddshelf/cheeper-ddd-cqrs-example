<?php

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\DomainEvent;

class EventStream implements \Iterator
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

    public function rewind()
    {
        reset($this->events);
    }

    public function current()
    {
        return current($this->events);
    }

    public function key()
    {
        return key($this->events);
    }

    public function next()
    {
        next($this->events);
    }

    public function valid()
    {
        return key($this->events) !== null;
    }
}

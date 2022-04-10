<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel;

use Cheeper\Chapter7\DomainModel\DomainEvent;

class EventStream implements \Iterator
{
    /**
     * @param DomainEvent[] $events
     */
    public function __construct(
        private string $aggregateId,
        private array $events
    ) {
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function rewind(): void
    {
        reset($this->events);
    }

    public function current(): mixed
    {
        return current($this->events);
    }

    public function key(): mixed
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

    public function isEmpty(): bool
    {
        return count($this->events) === 0;
    }
}
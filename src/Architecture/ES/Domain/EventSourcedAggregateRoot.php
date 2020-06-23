<?php

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\AggregateRoot;
use Architecture\CQRS\Domain\DomainEvent;

/**
 * @template T of DomainEvent
 * @extends AggregateRoot<T>
 */
//snippet event-sourced-aggregate-root
abstract class EventSourcedAggregateRoot extends AggregateRoot
{
    /**
     * @param EventStream<T> $events
     * @return EventSourcedAggregateRoot<T>
     */
    abstract public static function reconstitute(EventStream $events):
        EventSourcedAggregateRoot;

    /** @param EventStream<T> $history */
    public function replay(EventStream $history): void
    {
        foreach ($history as $event) {
            $this->applyThat($event);
        }
    }
}
//end-snippet

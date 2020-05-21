<?php

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\AggregateRoot;
use Architecture\CQRS\Domain\DomainEvent;

//snippet event-sourced-aggregate-root
abstract class EventSourcedAggregateRoot extends AggregateRoot
{
    abstract public static function reconstitute(EventStream $events):
        EventSourcedAggregateRoot;

    public function replay(EventStream $history): void
    {
        /** @var DomainEvent */
        foreach ($history as $event) {
            $this->applyThat($event);
        }
    }
}
//end-snippet

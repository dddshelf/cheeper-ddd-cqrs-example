<?php declare(strict_types=1);

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\AggregateRoot;
use Architecture\CQRS\Domain\DomainEvent;

//snippet event-sourced-aggregate-root
/**
 * @template T as DomainEvent
 * @template-extends AggregateRoot<T>
 */
abstract class EventSourcedAggregateRoot extends AggregateRoot
{
    /**
     * @param EventStream<T> $events
     * @return static<T>
     */
    abstract public static function reconstitute(EventStream $events): self;

    /**
     * @param EventStream<T> $history
     */
    public function replay(EventStream $history): void
    {
        foreach ($history as $event) {
            $this->applyThat($event);
        }
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Event;


//snippet in-memory-event-bus
use Cheeper\Chapter7\Application\Event\EventBus;
use Cheeper\Chapter7\DomainModel\DomainEvent;

final class InMemoryEventBus implements EventBus
{
    public function __construct(
        /** @param DomainEvent[] $events */
        private array $events = []
    )
    {
    }

    public function events(): array
    {
        return $this->events;
    }

    public function reset(): void
    {
        $this->events = [];
    }

    public function notify(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function notifyAll(array $domainEvents): void
    {
        \Functional\each($domainEvents, fn (DomainEvent $de) => $this->notify($de));
    }
}
//end-snippet

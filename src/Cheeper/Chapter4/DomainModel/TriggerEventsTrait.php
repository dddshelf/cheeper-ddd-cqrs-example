<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\DomainModel;

// snippet trigger-events-trait
trait TriggerEventsTrait
{
    /** @var DomainEvent[] */
    private array $domainEvents = [];

    /** @return DomainEvent[] */
    public function domainEvents(): array
    {
        return $this->domainEvents;
    }

    /** @return DomainEvent[] */
    public function retrieveAndFlushDomainEvents(): array
    {
        $events = $this->domainEvents();
        $this->resetDomainEvent();

        return $events;
    }

    public function notifyDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    private function resetDomainEvent(): void
    {
        $this->domainEvents = [];
    }
}
// end-snippet

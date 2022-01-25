<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel;

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

    public function notifyDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    public function resetDomainEvent(): void
    {
        $this->domainEvents = [];
    }
}
// end-snippet
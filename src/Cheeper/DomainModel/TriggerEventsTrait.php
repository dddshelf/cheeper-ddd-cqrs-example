<?php

declare(strict_types=1);

namespace Cheeper\DomainModel;

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
}
// end-snippet

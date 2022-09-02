<?php

declare(strict_types=1);

namespace Cheeper\DomainModel;

trait RecordsEvents
{
    /**
     * @psalm-var list<DomainEvent>
     * @var DomainEvent[]
     */
    private array $events = [];

    private function recordThat(DomainEvent $eventHappened): void
    {
        $this->events[] = $eventHappened;
    }

    /**
     * @psalm-return list<DomainEvent>
     * @return DomainEvent[]
     */
    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
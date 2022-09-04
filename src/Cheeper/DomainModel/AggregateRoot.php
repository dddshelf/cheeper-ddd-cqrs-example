<?php

declare(strict_types=1);

namespace Cheeper\DomainModel;

/** @template T of DomainEvent */
abstract class AggregateRoot
{
    /**
     * @psalm-var list<T>
     * @var DomainEvent[]
     */
    private array $events = [];

    /** @psalm-param T $eventHappened */
    protected function recordThat(DomainEvent $eventHappened): void
    {
        $this->events[] = $eventHappened;
    }

    /**
     * @psalm-return list<T>
     * @return DomainEvent[]
     */
    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}

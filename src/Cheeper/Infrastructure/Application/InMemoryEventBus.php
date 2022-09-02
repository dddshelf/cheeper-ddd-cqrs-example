<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\EventBus;
use Cheeper\DomainModel\DomainEvent;
use Psl\Vec;

final class InMemoryEventBus implements EventBus
{
    /**
     * @psalm-var list<DomainEvent>
     * @var DomainEvent[]
     */
    private array $events = [];

    public function publishAll(array $events): void
    {
        $this->events = Vec\concat($this->events, $events);
    }

    /**
     * @psalm-return list<DomainEvent>
     * @return DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}
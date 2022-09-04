<?php

declare(strict_types=1);

namespace Cheeper\Application;

use Cheeper\DomainModel\DomainEvent;

interface EventBus
{
    /**
     * @psalm-param list<DomainEvent> $events
     * @param DomainEvent[] $events
     */
    public function publishAll(array $events): void;
}

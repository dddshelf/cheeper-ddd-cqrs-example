<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application;

use Cheeper\Chapter7\DomainModel\DomainEvent;

//snippet event-bus
interface EventBus
{
    public function notify(DomainEvent $event): void;

    /** @param DomainEvent[] $domainEvents */
    public function notifyAll(array $domainEvents): void;
}
//end-snippet

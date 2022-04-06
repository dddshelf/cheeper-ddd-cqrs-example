<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel;

use Cheeper\Chapter7\DomainModel\DomainEvent;
use ReflectionClass;

// snippet code
trait EventSourcedTrait
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

    protected function applyThat(DomainEvent $event): void
    {
        $className = (new ReflectionClass($event))->getShortName();

        $modifier = 'apply' . $className;

        $this->$modifier($event);
    }

    public function replay(EventStream $history): void
    {
        foreach ($history as $event) {
            $this->applyThat($event);
        }
    }
}
// end-snippet

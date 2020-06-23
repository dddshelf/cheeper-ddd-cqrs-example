<?php

namespace Architecture\CQRS\Domain;

/** @template T of DomainEvent */
//snippet aggregate-root
class AggregateRoot
{
    /** @var T[] */
    private array $recordedEvents = [];

    /** @param T $event */
    protected function recordApplyAndPublishThat(DomainEvent $event): void
    {
        $this->recordThat($event);
        $this->applyThat($event);
        $this->publishThat($event);
    }

    /** @param T $event */
    protected function recordThat(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    protected function applyThat(DomainEvent $event): void
    {
        $className = (new \ReflectionClass($event))->getShortName();

        $modifier = 'apply' . $className;

        /** @phpstan-ignore-next-line */
        $this->$modifier($event);
    }

    protected function publishThat(DomainEvent $event): void
    {
        DomainEventPublisher::instance()->publish($event);
    }

    /** @return T[] */
    public function recordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearEvents(): void
    {
        $this->recordedEvents = [];
    }
}
//end-snippet

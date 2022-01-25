<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application\Event;

use Cheeper\Chapter7\Application\Event\EventBus;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

//snippet symfony-event-bus
final class SymfonyEventBus implements EventBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->messageBus = $eventBus;
    }

    public function notify(DomainEvent $event): void
    {
        $this->messageBus->dispatch($event);
    }

    public function notifyAll(array $domainEvents): void
    {
        \Functional\each($domainEvents, fn (DomainEvent $de) => $this->notify($de));
    }
}
//end-snippet

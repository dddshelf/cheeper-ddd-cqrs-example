<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Event;

use Cheeper\AllChapters\DomainModel\DomainEvent;
use Cheeper\Chapter6\Application\Event\EventBus;
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

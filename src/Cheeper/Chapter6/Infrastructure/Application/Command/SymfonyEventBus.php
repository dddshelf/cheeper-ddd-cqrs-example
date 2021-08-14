<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application\Command;

use Cheeper\Chapter6\Application\Command\EventBus;
use Cheeper\DomainModel\DomainEvent;
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

    public function notify(DomainEvent $event)
    {
        return $this->handle($event);
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Psl\Iter;

final class SymfonyMessengerEventBus implements EventBus
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function publishAll(array $events): void
    {
        Iter\apply($events, $this->messageBus->dispatch(...));
    }
}
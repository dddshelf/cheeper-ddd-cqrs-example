<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\EventBus;
use Psl\Iter;
use Symfony\Component\Messenger\MessageBusInterface;

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

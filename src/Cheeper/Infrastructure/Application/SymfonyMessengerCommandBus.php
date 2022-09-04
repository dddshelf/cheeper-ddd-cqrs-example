<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\Command;
use Cheeper\Application\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerCommandBus implements CommandBus
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
    }

    public function handle(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
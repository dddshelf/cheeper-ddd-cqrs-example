<?php

namespace App\Messenger;

use Cheeper\Application\Command\AsyncCommand;
use Cheeper\Application\Command\SyncCommand;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /** @param AsyncCommand|SyncCommand|object $command */
    public function execute($command): Envelope
    {
        if ($command instanceof SyncCommand) {
            return $this->handle($command);
        }

        return $this->messageBus->dispatch($command);
    }
}

<?php

namespace App\Messenger;

use Cheeper\Application\Command\AsyncCommand;
use Cheeper\Application\Command\SyncCommand;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

// snippet command-bus
final class CommandBus
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->commandBus = $bus;
    }

    public function handle(object $command): Envelope
    {
        return $this->commandBus->dispatch($command);
    }
}
//end-snippet

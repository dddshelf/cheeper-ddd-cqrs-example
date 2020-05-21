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

    /**
     * @template T
     * @param AsyncCommand|SyncCommand|object $command
     * @return T|Envelope The handler returned value
     */
    public function execute($command)
    {
        if ($command instanceof SyncCommand) {
            return $this->handle($command);
        }

        return $this->messageBus->dispatch($command);
    }
}

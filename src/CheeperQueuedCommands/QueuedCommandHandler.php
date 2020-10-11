<?php

declare(strict_types=1);

namespace CheeperQueuedCommands;

use App\Messenger\CommandBus;

//snippet queued-command-handler
final class QueuedCommandHandler
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(QueuedCommand $message): void
    {
        $this->commandBus->handle($message->command());
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Application\Command;

use App\Messenger\CommandBus;

//snippet queued-command-handler
final class QueuedCommandHandler
{
    public function __construct(
        private CommandBus $commandBus
    ) {
    }

    public function __invoke(QueuedCommand $message): void
    {
        $this->commandBus->handle($message->command());
    }
}
//end-snippet

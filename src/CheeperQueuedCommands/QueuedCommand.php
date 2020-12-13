<?php

declare(strict_types=1);

namespace CheeperQueuedCommands;

use Cheeper\Application\Command\AsyncCommand;

//snippet queued-command
final class QueuedCommand
{
    public function __construct(
        private AsyncCommand $command
    ) { }

    public function command(): AsyncCommand
    {
        return $this->command;
    }
}
//end-snippet

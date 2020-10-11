<?php

declare(strict_types=1);

namespace CheeperQueuedCommands;

//snippet queued-command
use Cheeper\Application\Command\AsyncCommand;

final class QueuedCommand
{
    private AsyncCommand $command;

    public function __construct(AsyncCommand $command)
    {
        $this->command = $command;
    }

    public function command(): AsyncCommand
    {
        return $this->command;
    }
}
//end-snippet

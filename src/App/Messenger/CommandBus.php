<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

// snippet command-bus
final class CommandBus
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
    }

    public function handle(object $command): Envelope
    {
        return $this->commandBus->dispatch($command);
    }
}
//end-snippet

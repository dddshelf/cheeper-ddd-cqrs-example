<?php

declare(strict_types=1);

namespace App\Messenger;

use function Functional\first;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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
        try {
            $envelope = $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw first($exception->getNestedExceptions());
        }

        return $envelope;
    }
}
//end-snippet

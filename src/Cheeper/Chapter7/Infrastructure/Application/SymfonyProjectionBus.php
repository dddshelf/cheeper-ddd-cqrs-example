<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application;

use Cheeper\Chapter7\Application\Projection;
use Cheeper\Chapter7\Application\ProjectionBus;
use function Functional\first;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

//snippet symfony-projection-bus
final class SymfonyProjectionBus implements ProjectionBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $projectionBus)
    {
        $this->messageBus = $projectionBus;
    }

    public function project(Projection $projection): void
    {
        try {
            $this->messageBus->dispatch($projection);
        } catch (HandlerFailedException $e) {
            throw first($e->getNestedExceptions());
        }
    }
}
//end-snippet

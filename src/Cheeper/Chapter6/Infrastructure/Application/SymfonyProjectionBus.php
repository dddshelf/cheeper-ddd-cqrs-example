<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Infrastructure\Application;

use Cheeper\Chapter6\Application\Projection;
use Cheeper\Chapter6\Application\ProjectionBus;
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
        $this->messageBus->dispatch($projection);
    }
}
//end-snippet

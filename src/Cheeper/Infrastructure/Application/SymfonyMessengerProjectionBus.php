<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\Projection;
use Cheeper\Application\ProjectionBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class SymfonyMessengerProjectionBus implements ProjectionBus
{
    public function __construct(
        private readonly MessageBusInterface $projectionBus
    ) {
    }

    public function project(Projection $projection): void
    {
        $this->projectionBus->dispatch($projection);
    }
}

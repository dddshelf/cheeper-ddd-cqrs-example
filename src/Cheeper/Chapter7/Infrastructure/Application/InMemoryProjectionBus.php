<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Application;

use Cheeper\Chapter7\Application\Projection;
use Cheeper\Chapter7\Application\ProjectionBus;

//snippet in-memory-event-bus
final class InMemoryProjectionBus implements ProjectionBus
{
    public function __construct(
        /** @param Projection[] $projections */
        private array $projections = []
    ) {
    }

    public function projections(): array
    {
        return $this->projections;
    }

    public function reset(): void
    {
        $this->projections = [];
    }

    public function project(Projection $projection): void
    {
        $this->projections[] = $projection;
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Application;

use Cheeper\Application\Projection;
use Cheeper\Application\ProjectionBus;

final class InMemoryProjectionBus implements ProjectionBus
{
    /**
     * @psalm-var list<Projection>
     * @var Projection[]
     */
    private array $runProjections = [];

    public function project(Projection $projection): void
    {
        $this->runProjections[] = $projection;
    }

    /**
     * @psalm-return list<Projection>
     * @return Projection[]
     */
    public function getProjections(): array
    {
        return $this->runProjections;
    }
}

<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\AggregateRoot;

final class Snapshot
{
    public function __construct(
        private AggregateRoot $aggregate,
        private int           $version
    ) {
    }

    public function aggregate(): AggregateRoot
    {
        return $this->aggregate;
    }

    public function version(): int
    {
        return $this->version;
    }
}

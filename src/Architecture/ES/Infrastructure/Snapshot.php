<?php declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

use Architecture\CQRS\Domain\AggregateRoot;

/** @template T of AggregateRoot */
class Snapshot
{
    /** @var T */
    private AggregateRoot $aggregate;
    private int $version;

    /** @param T $aggregate */
    public function __construct(AggregateRoot $aggregate, int $version)
    {
        $this->aggregate = $aggregate;
        $this->version = $version;
    }

    /** @return T */
    public function aggregate(): AggregateRoot
    {
        return $this->aggregate;
    }

    public function version(): int
    {
        return $this->version;
    }
}

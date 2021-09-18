<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

final class InMemorySnapshotRepository implements SnapshotRepository
{
    /** @var Snapshot[] */
    private array $snapshots = [];

    public function byId(string $id): ?Snapshot
    {
        return $this->snapshots[$id] ?? null;
    }

    public function save(string $id, Snapshot $snapshot): void
    {
        $this->snapshots[$id] = $snapshot;
    }

    public function has(string $id, int $version): bool
    {
        $snapshot = $this->snapshots[$id] ?? null;

        if (null === $snapshot) {
            return false;
        }

        return $snapshot->version() === $version;
    }
}

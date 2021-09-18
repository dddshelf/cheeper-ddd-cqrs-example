<?php

declare(strict_types=1);

namespace Architecture\ES\Infrastructure;

interface SnapshotRepository
{
    public function byId(string $id): ?Snapshot;
    public function save(string $id, Snapshot $snapshot): void;
    public function has(string $id, int $version): bool;
}

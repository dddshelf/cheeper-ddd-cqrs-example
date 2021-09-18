<?php

declare(strict_types=1);

namespace Architecture\ES\Domain;

interface EventStore
{
    public function append(EventStream $eventstream): void;
    public function getEventsFor(string $id): EventStream;
    public function fromVersion(string $id, int $version): EventStream;
    public function countEventsFor(string $id): int;
}

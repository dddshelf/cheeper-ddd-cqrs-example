<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel;

//snippet code
interface EventStore
{
    public function append(EventStream $eventStream): void;
    public function getEventsFor(string $id): EventStream;

    public function fromVersion(string $id, int $version): EventStream;
    public function countEventsFor(string $id): int;
}
//end-snippet
<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Clock;

use DateTimeImmutable;

final class DateCollectionClockStrategy implements ClockStrategy
{
    private int $iterator;

    public function __construct(
        private readonly array $collection = []
    ) {
        $this->iterator = 0;
    }

    public function now(): DateTimeImmutable
    {
        if (empty($this->collection)) {
            throw new \InvalidArgumentException('Date collection is empty');
        }

        $currentDate = $this->collection[$this->iterator];
        $this->iterator = ($this->iterator + 1) % count($this->collection);

        return $currentDate;
    }
}

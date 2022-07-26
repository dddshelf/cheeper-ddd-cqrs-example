<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Clock;

use DateTimeImmutable;

interface ClockStrategy
{
    public function now(): DateTimeImmutable;
}

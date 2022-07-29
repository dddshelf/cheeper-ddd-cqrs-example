<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Clock;

use DateTimeImmutable;

final class DefaultClockStrategy implements ClockStrategy
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable(
            'now',
            new \DateTimeZone(
                'UTC'
            )
        );
    }
}

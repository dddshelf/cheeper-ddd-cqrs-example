<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Clock;

use DateTimeImmutable;

class DefaultClockStrategy implements ClockStrategy
{
    public function now(): DateTimeImmutable {
        return new DateTimeImmutable(
            'now',
            new \DateTimeZone(
                'UTC'
            )
        );
    }
}
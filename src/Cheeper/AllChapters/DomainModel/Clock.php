<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel;

use Cheeper\AllChapters\DomainModel\Clock\ClockStrategy;
use Cheeper\AllChapters\DomainModel\Clock\DefaultClockStrategy;
use DateTimeImmutable;

final class Clock
{
    private static ?Clock $instance = null;
    private ClockStrategy $strategy;

    private function __construct()
    {
        $this->strategy = new DefaultClockStrategy();
    }

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function now(): DateTimeImmutable
    {
        return $this->strategy->now();
    }

    public function changeStrategy(ClockStrategy $clockStrategy): self
    {
        $this->strategy = $clockStrategy;

        return $this;
    }
}

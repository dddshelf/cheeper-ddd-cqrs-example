<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel;

use Cheeper\AllChapters\DomainModel\Clock\ClockStrategy;
use Cheeper\AllChapters\DomainModel\Clock\DefaultClockStrategy;
use DateTimeImmutable;

class Clock
{
    protected static ?Clock $instance = null;
    protected ClockStrategy $strategy;

    private function __construct()
    {
        $this->strategy = new DefaultClockStrategy();
    }

    public static function instance(): static
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
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

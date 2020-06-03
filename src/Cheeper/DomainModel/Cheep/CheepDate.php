<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Assert\Assertion;
use Cheeper\DomainModel\Common\ValueObject;
use DateTimeImmutable;

final class CheepDate extends ValueObject
{
    private DateTimeImmutable $date;

    public function __construct(string $date)
    {
        $this->setDate($date);
    }

    public function date(): string
    {
        return $this->date->format('Y-m-d');
    }

    private function setDate(string $date): void
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        if (!$date) {
            throw new \InvalidArgumentException("'$date' is not a valid datetime (Y-m-d formatted).");
        }

        $this->date = $date;
    }
}

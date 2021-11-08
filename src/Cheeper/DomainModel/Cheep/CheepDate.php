<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Common\ValueObject;
use InvalidArgumentException;
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
        return $this->date->format('Y-m-d H:i:s');
    }

    private function setDate(string $date): void
    {
        $dateInstance = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);

        if ($dateInstance === false) {
            throw new InvalidArgumentException("'$date' is not a valid datetime (Y-m-d formatted).");
        }

        $this->date = $dateInstance;
    }
}

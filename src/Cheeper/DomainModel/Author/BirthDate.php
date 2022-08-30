<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;
use DateTimeImmutable;

/** @psalm-immutable  */
final class BirthDate extends ValueObject
{
    /** @psalm-allow-private-mutation  */
    private DateTimeImmutable $date;

    public function __construct(string $date)
    {
        $this->setDate($date);
    }

    /**
     * @psalm-pure
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    private function setDate(string $date): void
    {
        $dateInstance = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        if (false === $dateInstance) {
            throw new \InvalidArgumentException("'$date' is not a valid datetime (Y-m-d formatted).");
        }

        $this->date = $dateInstance;
    }
}

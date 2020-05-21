<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;
use DateTimeImmutable;

final class BirthDate extends ValueObject
{
    private DateTimeImmutable $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }
}

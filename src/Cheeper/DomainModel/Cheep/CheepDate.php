<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Common\ValueObject;
use DateTimeImmutable;

final class CheepDate extends ValueObject
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

<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;
use function Safe\sprintf;

final class EmailAddress extends ValueObject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->setEmail($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function setEmail(string $value): void
    {
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email %s', $value));
        }

        $this->value = $value;
    }
}
<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;
use Stringable;

final class EmailAddress extends ValueObject implements Stringable
{
    public function __construct(
        private string $value
    ) {
        $this->assertEmailIsValid($value);
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function assertEmailIsValid(string $value): void
    {
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email %s', $value));
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;
use Stringable;

/** @psalm-immutable  */
final class UserName extends ValueObject implements Stringable
{
    private function __construct(
        private readonly string $userName
    ) {
        $this->assertUserNameIsNotEmpty($userName);
    }

    /** @psalm-pure */
    public static function pick(string $userName): self
    {
        return new self($userName);
    }

    public function userName(): string
    {
        return $this->userName;
    }

    public function equalsTo(UserName $userName): bool
    {
        return $this->userName === $userName->userName;
    }

    private function assertUserNameIsNotEmpty(string $userName): void
    {
        if ('' === $userName) {
            throw new \InvalidArgumentException("Username cannot be empty");
        }
    }

    public function toString(): string
    {
        return $this->userName;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

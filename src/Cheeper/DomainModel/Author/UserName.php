<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;
use Stringable;

/** @psalm-immutable  */
final class UserName extends ValueObject implements Stringable
{
    /** @psalm-param non-empty-string $userName */
    private function __construct(
        public readonly string $userName
    ) {
        $this->assertNotEmpty($this->userName);
    }

    /**
     * @psalm-param non-empty-string $userName
     *
     * @psalm-pure
     */
    public static function pick(string $userName): self
    {
        return new self($userName);
    }

    public function equalsTo(UserName $userName): bool
    {
        return $this->userName === $userName->userName;
    }

    public function __toString(): string
    {
        return $this->userName;
    }

    private function assertNotEmpty(string $userName): void
    {
        if ('' === $userName) {
            throw new \InvalidArgumentException("Username cannot be empty!");
        }
    }
}

<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\Common\ValueObject;

final class UserName extends ValueObject
{
    private string $userName;

    private function __construct(string $userName)
    {
        $this->setUserName($userName);
    }

    private function setUserName(string $userName): void
    {
        $this->assertNotEmpty($userName);

        $this->userName = $userName;
    }

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

    private function assertNotEmpty(string $userName): void
    {
        if ('' === $userName) {
            throw new \InvalidArgumentException("Username cannot be empty");
        }
    }
}

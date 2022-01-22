<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Cheep;

use Assert\Assertion;
use Cheeper\AllChapters\DomainModel\Common\ValueObject;

final class CheepMessage extends ValueObject
{
    private const MAX_LENGTH = 260;

    private function __construct(
        private string $message
    ) {
        $this->assertMessageIsValid($message);
    }

    public static function write(string $message): self
    {
        return new static($message);
    }

    private function assertMessageIsValid(string $message): void
    {
        Assertion::notEmpty($message);
        Assertion::maxLength($message, self::MAX_LENGTH);
    }

    public function message(): string
    {
        return $this->message;
    }

    public function equalsTo(self $other): bool
    {
        return $this->message === $other->message;
    }
}

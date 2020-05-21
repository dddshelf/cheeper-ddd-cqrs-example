<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Assert\Assertion;
use Cheeper\DomainModel\Common\ValueObject;

final class CheepMessage extends ValueObject
{
    private const MAX_LENGTH = 260;

    private string $message;

    public static function write(string $message): self
    {
        return new static($message);
    }

    private function __construct(string $message)
    {
        $this->setMessage($message);
    }

    private function setMessage(string $message): self
    {
        Assertion::notEmpty($message);
        Assertion::maxLength($message, self::MAX_LENGTH);

        $this->message = $message;

        return $this;
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

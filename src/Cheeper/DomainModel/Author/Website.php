<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Cheeper\DomainModel\Common\ValueObject;
use Stringable;

/** @psalm-immutable  */
final class Website extends ValueObject implements Stringable
{
    public function __construct(
        private readonly string $uri
    ) {
        $this->assertUriIsValid($this->uri);
    }

    /** @psalm-pure */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->uri;
    }

    /** @psalm-suppress ImpureMethodCall */
    private function assertUriIsValid(string $uri): void
    {
        try {
            Assertion::url($uri);
        } catch (AssertionFailedException) {
            throw new \InvalidArgumentException("Invalid URL given");
        }
    }

    public function __toString(): string
    {
        return $this->uri;
    }
}

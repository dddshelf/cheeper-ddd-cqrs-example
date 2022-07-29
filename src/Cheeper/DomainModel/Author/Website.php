<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Cheeper\DomainModel\Common\ValueObject;
use Stringable;

final class Website extends ValueObject implements Stringable
{
    public function __construct(
        private string $uri
    ) {
        $this->setUri($uri);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function setUri(string $uri): void
    {
        $this->assertValidUrl($uri);

        $this->uri = $uri;
    }

    public function toString(): string
    {
        return $this->uri;
    }

    private function assertValidUrl(string $uri): void
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
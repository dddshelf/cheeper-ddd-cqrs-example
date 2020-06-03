<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Cheeper\DomainModel\Common\ValueObject;

final class Website extends ValueObject
{
    private string $uri;

    public function __construct(string $uri)
    {
        $this->setUri($uri);
    }

    private function setUri(string $uri): void
    {
        $this->assertValidUrl($uri);

        $this->uri = $uri;
    }

    private function assertValidUrl(string $uri): void
    {
        try {
            Assertion::url($uri);
        } catch (AssertionFailedException $assertionFailedException) {
            throw new \InvalidArgumentException("Invalid URL given");
        }
    }
}

<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Assert\Assertion;
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
        Assertion::url($uri);

        $this->uri = $uri;
    }
}

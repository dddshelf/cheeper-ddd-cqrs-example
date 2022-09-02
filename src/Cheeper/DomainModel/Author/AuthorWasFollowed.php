<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\DomainEvent;

/** @psalm-immutable */
final class AuthorWasFollowed implements DomainEvent
{
    public function __construct(
        public readonly string $fromAuthorId,
        public readonly string $toAuthorId,
        public readonly \DateTimeImmutable $occurredOn,
    ) {
    }
}
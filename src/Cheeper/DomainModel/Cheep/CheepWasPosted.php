<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\DomainEvent;

/** @psalm-immutable */
final class CheepWasPosted implements DomainEvent
{
    /**
     * @psalm-param non-empty-string $authorId
     * @psalm-param non-empty-string $cheepId
     * @psalm-param non-empty-string $cheepMessage
     */
    public function __construct(
        public readonly string $authorId,
        public readonly string $cheepId,
        public readonly string $cheepMessage,
        public readonly \DateTimeImmutable $cheepDate,
        public readonly \DateTimeImmutable $occurredOn,
    ) {
    }
}

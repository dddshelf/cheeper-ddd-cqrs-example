<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

use Cheeper\DomainModel\DomainEvent;
use DateTimeImmutable;
use DateTimeZone;

// snippet new-author-signed-domain-event
class NewAuthorSigned implements DomainEvent
{
    private function __construct(
        private string $authorId,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromAuthor(Author $author): self
    {
        return new self(
            $author->authorId()->toString(),
            new DateTimeImmutable(
                timezone: new DateTimeZone("UTC")
            )
        );
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

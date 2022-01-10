<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Author;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\DomainModel\DomainEvent;
use DateTimeImmutable;
use DateTimeZone;

// snippet new-author-signed-domain-event
class NewAuthorSigned implements DomainEvent
{
    use MessageTrait;

    private function __construct(
        private string $authorId,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromAuthor(Author $author): static
    {
        return new static(
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

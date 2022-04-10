<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use DateTimeImmutable;

// snippet new-author-signed-domain-event
class NewAuthorSigned implements DomainEvent
{
    use MessageTrait;

    private function __construct(
        private string $authorId,
        private string $authorUsername,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromAuthor(Author $author): static
    {
        return new static(
            $author->authorId()->toString(),
            $author->userName()->toString(),
            Clock::instance()->now(),
        );
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function authorUsername(): string
    {
        return $this->authorUsername;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

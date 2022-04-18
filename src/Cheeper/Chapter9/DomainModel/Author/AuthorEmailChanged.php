<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use DateTimeImmutable;

// snippet code
final class AuthorEmailChanged implements DomainEvent
{
    use MessageTrait;

    private function __construct(
        private string $authorId,
        private string $authorEmail,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function ofAuthorIdAndNewEmail(
        AuthorId $authorId,
        EmailAddress $emailAddress
    ): self {
        return new self(
            $authorId->toString(),
            $emailAddress->value(),
            Clock::instance()->now()
        );
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function authorEmail(): string
    {
        return $this->authorEmail;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

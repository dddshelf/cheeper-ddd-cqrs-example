<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use DateTimeImmutable;

// snippet code
class NewAuthorSigned implements DomainEvent
{
    use MessageTrait;

    private DateTimeImmutable $occurredOn;

    private function __construct(
        private string $authorId,
        private string $authorUsername,
        private string $authorEmail,
        private ?string $authorName = null,
        private ?string $authorBiography = null,
        private ?string $authorLocation = null,
        private ?string $authorWebsite = null,
        private ?DateTimeImmutable $authorBirthDate = null,
        ?DateTimeImmutable $occurredOn = null,
    ) {
        if (null === $occurredOn) {
            $occurredOn = Clock::instance()->now();
        }

        $this->occurredOn = $occurredOn;
    }

    public static function fromAuthor(Author $author): static
    {
        return new static(
            $author->authorId()->toString(),
            $author->userName()->toString(),
            $author->email()->value(),
            $author->name(),
            $author->biography(),
            $author->location(),
            $author->website(),
            $author->birthDate()->date(),
            Clock::instance()->now()
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

    public function authorEmail(): string
    {
        return $this->authorEmail;
    }

    public function authorName(): ?string
    {
        return $this->authorName;
    }

    public function authorBiography(): ?string
    {
        return $this->authorBiography;
    }

    public function authorLocation(): ?string
    {
        return $this->authorLocation;
    }

    public function authorWebsite(): ?string
    {
        return $this->authorWebsite;
    }

    public function authorBirthDate(): ?DateTimeImmutable
    {
        return $this->authorBirthDate;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

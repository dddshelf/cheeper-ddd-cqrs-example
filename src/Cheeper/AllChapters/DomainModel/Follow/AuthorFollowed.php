<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\DomainEvent;
use DateTimeImmutable;
use DateTimeZone;

// snippet author-followed-domain-event
final class AuthorFollowed implements DomainEvent
{
    private function __construct(
        private string $followId,
        private string $fromAuthorId,
        private string $toAuthorId,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromFollow(Follow $follow): self
    {
        return new self(
            $follow->followId()->toString(),
            $follow->fromAuthorId()->toString(),
            $follow->toAuthorId()->toString(),
            new DateTimeImmutable(
                timezone: new DateTimeZone("UTC")
            )
        );
    }

    public function followId(): string
    {
        return $this->followId;
    }

    public function fromAuthorId(): string
    {
        return $this->fromAuthorId;
    }

    public function toAuthorId(): string
    {
        return $this->toAuthorId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

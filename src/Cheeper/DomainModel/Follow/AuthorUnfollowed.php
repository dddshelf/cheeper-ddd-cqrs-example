<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

use Cheeper\DomainModel\DomainEvent;

// snippet author-unfollowed-domain-event
final class AuthorUnfollowed implements DomainEvent
{
    private function __construct(
        private string $followId,
        private string $fromAuthorId,
        private string $toAuthorId,
    ) {
    }

    public static function fromFollow(Follow $follow): self
    {
        return new self(
            $follow->followId()->toString(),
            $follow->fromAuthorId()->toString(),
            $follow->toAuthorId()->toString(),
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
}
// end-snippet

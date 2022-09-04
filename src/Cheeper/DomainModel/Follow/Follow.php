<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

use Cheeper\DomainModel\AggregateRoot;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorWasFollowed;
use Cheeper\DomainModel\Clock\Clock;

/**
 * @final
 * @extends AggregateRoot<AuthorWasFollowed>
 */
class Follow extends AggregateRoot
{
    /**
     * @psalm-param non-empty-string $followId
     * @psalm-param non-empty-string $fromAuthorId
     * @psalm-param non-empty-string $toAuthorId
     */
    private function __construct(
        private string $followId,
        private string $fromAuthorId,
        private string $toAuthorId,
    ) {
    }

    public static function fromAuthorToAuthor(
        FollowId $followId,
        AuthorId $fromAuthorId,
        AuthorId $toAuthorId,
    ): self {
        $follow = new self(
            followId: $followId->id,
            fromAuthorId: $fromAuthorId->id,
            toAuthorId: $toAuthorId->id,
        );

        $follow->recordThat(
            new AuthorWasFollowed(
                $fromAuthorId->id,
                $toAuthorId->id,
                Clock::instance()->now()
            )
        );

        return $follow;
    }

    public function fromAuthorId(): AuthorId
    {
        return AuthorId::fromString($this->fromAuthorId);
    }

    public function toAuthorId(): AuthorId
    {
        return AuthorId::fromString($this->toAuthorId);
    }

    public function followId(): FollowId
    {
        return FollowId::fromString($this->followId);
    }
}

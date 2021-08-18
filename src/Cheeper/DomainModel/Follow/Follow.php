<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\TriggerEventsTrait;

// snippet follow-entity-with-events
class Follow
{
    use TriggerEventsTrait;

    private function __construct(
        private FollowId $followId,
        private AuthorId $fromAuthorId,
        private AuthorId $toAuthorId,
    ) {
        $this->notifyDomainEvent(
            AuthorFollowed::fromFollow($this)
        );

        /**
         * As an alternative, we can use a Singleton
         * implementing an Observer pattern with
         * Subscribers that will publish the triggered
         * Domain Events into a queue system like
         * Rabbit. It's useful for Legacy projects
         * because you can trigger any Domain Event
         * from any place in your code, not only
         * Entities.
         *
         * DomainEventPublisher::getInstance()
         *     ->notifyDomainEvent(
         *         AuthorFollowed::fromFollow($this)
         *     )
         * );
         */
    }

    public static function fromAuthorToAuthor(
        FollowId $followId,
        AuthorId $fromAuthorId,
        AuthorId $toAuthorId,
    ): self
    {
        return new self(
            followId: $followId,
            fromAuthorId: $fromAuthorId,
            toAuthorId: $toAuthorId
        );
    }

    final public function fromAuthorId(): AuthorId
    {
        return $this->fromAuthorId;
    }

    final public function toAuthorId(): AuthorId
    {
        return $this->toAuthorId;
    }

    final public function followId(): FollowId
    {
        return $this->followId;
    }
}
// end-snippet

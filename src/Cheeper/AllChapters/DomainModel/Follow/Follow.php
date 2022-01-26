<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\TriggerEventsTrait;

// snippet follow-entity-with-events
class Follow
{
    use TriggerEventsTrait;

    protected function __construct(
        protected FollowId $followId,
        protected AuthorId $fromAuthorId,
        protected AuthorId $toAuthorId,
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
    ): static {
        return new static(
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

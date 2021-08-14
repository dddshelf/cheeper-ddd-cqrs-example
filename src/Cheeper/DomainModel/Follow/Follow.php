<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\TriggerEventsTrait;

// snippet follow-entity-with-events
class Follow
{
    use TriggerEventsTrait;

    public function __construct(
        private FollowId $followId,
        private AuthorId $fromAuthorId,
        private AuthorId $toAuthorId,
    ) {
        $this->notifyDomainEvent(
            AuthorFollowed::fromFollow($this)
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

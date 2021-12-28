<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Follow;

use Cheeper\DomainModel\Follow\Follow as FollowChapter6;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\FollowId;
use Cheeper\DomainModel\TriggerEventsTrait;

class Follow extends FollowChapter6
{
    use TriggerEventsTrait;

    protected function __construct(
        protected FollowId $followId,
        protected AuthorId $fromAuthorId,
        protected AuthorId $toAuthorId,
    ) {
        $this->notifyDomainEvent(
            AuthorFollowed::fromFollow($this)
                ->stampAsNewMessage()
        );
    }
}

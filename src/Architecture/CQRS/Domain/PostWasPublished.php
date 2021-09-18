<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

final class PostWasPublished extends DomainEvent
{
    public function __construct(
        private PostId $postId
    ) {
    }

    public function postId(): PostId
    {
        return $this->postId;
    }
}

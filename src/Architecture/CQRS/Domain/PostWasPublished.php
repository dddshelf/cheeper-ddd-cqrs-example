<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

class PostWasPublished extends DomainEvent
{
    private PostId $postId;

    public function __construct(PostId $postId)
    {
        $this->postId = $postId;
    }

    public function postId(): PostId
    {
        return $this->postId;
    }
}

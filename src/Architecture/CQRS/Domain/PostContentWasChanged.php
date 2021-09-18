<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

final class PostContentWasChanged extends DomainEvent
{
    public function __construct(
        private PostId $postId,
        private string $content
    ) {
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function content(): string
    {
        return $this->content;
    }
}

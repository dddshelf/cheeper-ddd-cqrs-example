<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

final class PostWasCreated extends DomainEvent
{
    public function __construct(
        private PostId $postId,
        private string $title,
        private string $content
    ) {
    }

    public function postId(): PostId
    {
        return $this->postId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }
}

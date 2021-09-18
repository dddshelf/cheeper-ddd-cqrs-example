<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

final class PostTitleWasChanged extends DomainEvent
{
    public function __construct(
        private PostId $postId,
        private string $title
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
}

<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

class PostTitleWasChanged extends DomainEvent
{
    private PostId $postId;
    private string $title;

    public function __construct(PostId $postId, string $title)
    {
        $this->postId = $postId;
        $this->title = $title;
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

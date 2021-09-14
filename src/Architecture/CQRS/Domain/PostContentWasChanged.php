<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

class PostContentWasChanged extends DomainEvent
{
    private PostId $postId;
    private string $content;

    public function __construct(PostId $postId, string $content)
    {
        $this->postId = $postId;
        $this->content = $content;
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

<?php declare(strict_types=1);

namespace Architecture\CQRS\Domain;

class PostWasCreated extends DomainEvent
{
    private PostId $postId;
    private string $title;
    private string $content;

    public function __construct(PostId $postId, string $title, string $content)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
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

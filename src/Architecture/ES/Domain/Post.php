<?php

namespace Architecture\ES\Domain;

use Architecture\CQRS\Domain\CategoryId;
use Architecture\CQRS\Domain\PostContentWasChanged;
use Architecture\CQRS\Domain\PostId;
use Architecture\CQRS\Domain\PostTitleWasChanged;
use Architecture\CQRS\Domain\PostWasCategorized;
use Architecture\CQRS\Domain\PostWasCreated;
use Architecture\CQRS\Domain\PostWasPublished;

/**
 * @psalm-type PostEvents = \Architecture\CQRS\Domain\PostWasCreated|\Architecture\CQRS\Domain\PostWasPublished|\Architecture\CQRS\Domain\PostWasCategorized|\Architecture\CQRS\Domain\PostContentWasChanged|\Architecture\CQRS\Domain\PostTitleWasChanged
 * @extends EventSourcedAggregateRoot<PostEvents>
 */
//snippet post
class Post extends EventSourcedAggregateRoot
{
    //ignore
    private PostId $id;
    private ?string $title = null;
    private ?string $content = null;
    private bool $published = false;
    /** @var CategoryId[] */
    private array $categories = [];

    protected function __construct(PostId $id)
    {
        $this->id = $id;
    }

    public static function writeNewFrom(string $title, string $content): self
    {
        $postId = PostId::create();

        $post = new self($postId);

        $post->recordApplyAndPublishThat(
            new PostWasCreated($postId, $title, $content)
        );

        return $post;
    }

    public function publish(): void
    {
        $this->recordApplyAndPublishThat(
            new PostWasPublished($this->id)
        );
    }

    public function categorizeIn(CategoryId $categoryId): void
    {
        $this->recordApplyAndPublishThat(
            new PostWasCategorized($this->id, $categoryId)
        );
    }

    public function changeContentFor(string $newContent): void
    {
        $this->recordApplyAndPublishThat(
            new PostContentWasChanged($this->id, $newContent)
        );
    }

    public function changeTitleFor(string $newTitle): void
    {
        $this->recordApplyAndPublishThat(
            new PostTitleWasChanged($this->id, $newTitle)
        );
    }

    public function id(): PostId
    {
        return $this->id;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    /** @return CategoryId[] */
    public function categories(): array
    {
        return array_values($this->categories);
    }

    public function isPublished(): bool
    {
        return $this->published === true;
    }

    protected function applyPostWasCreated(PostWasCreated $event): void
    {
        $this->id = $event->postId();
        $this->title = $event->title();
        $this->content = $event->content();
    }

    protected function applyPostWasPublished(PostWasPublished $event): void
    {
        $this->published = true;
    }

    protected function applyPostWasCategorized(PostWasCategorized $event): void
    {
        $this->categories[$event->categoryId()->id()] = $event->categoryId();
    }

    protected function applyPostContentWasChanged(PostContentWasChanged $event): void
    {
        $this->content = $event->content();
    }

    protected function applyPostTitleWasChanged(PostTitleWasChanged $event): void
    {
        $this->title = $event->title();
    }
    //end-ignore

    /** @return self */
    public static function reconstitute(EventStream $history): self
    {
        $post = new self(new PostId($history->getAggregateId()));

        $post->replay($history);

        return $post;
    }
}
//end-snippet

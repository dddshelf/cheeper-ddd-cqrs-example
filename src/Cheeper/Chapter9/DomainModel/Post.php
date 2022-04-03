<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel;

//snippet post
class Post extends AggregateRoot
{
    //ignore
    private PostId $id;
    private ?string $title = null;
    private ?string $content = null;
    private bool $published = false;
    private array $categories = [];

    protected function __construct(PostId $id)
    {
        $this->id = $id;
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

    public function categories(): array
    {
        return array_values($this->categories);
    }

    public function isPublished(): bool
    {
        return $this->published === true;
    }
    //end-ignore

    public static function writeNewFrom(string $title, string $content): self
    {
        $postId = PostId::create();

        $post = new static($postId);

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
}
//end-snippet

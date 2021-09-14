<?php declare(strict_types=1);

namespace CheeperHexagonal;

class Post
{
    private ?PostId $id = null;
    private string $title;
    private string $content;

    public static function writeNewFrom(string $title, string $content): self
    {
        return new self($title, $content);
    }

    private function __construct(string $title, string $content)
    {
        $this->setTitle($title);
        $this->setContent($content);
    }

    private function setTitle(string $title): void
    {
        if ('' === $title) {
            throw new \RuntimeException('Title cannot be empty');
        }

        $this->title = $title;
    }

    private function setContent(string $content): void
    {
        if ('' === $content) {
            throw new \RuntimeException('Content cannot be empty');
        }

        $this->content = $content;
    }

    public function setId(PostId $id): void
    {
        $this->id = $id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function id(): ?PostId
    {
        return $this->id;
    }
}

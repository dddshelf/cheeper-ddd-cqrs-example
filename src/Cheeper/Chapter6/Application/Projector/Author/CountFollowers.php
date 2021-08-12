<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Projector;

//snippet count-followers
final class CountFollowers
{
    private string $authorId;

    public static function ofAuthor(string $authorId): self
    {
        return new self($authorId);
    }

    private function __construct(string $authorId)
    {
        $this->authorId = $authorId;
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet

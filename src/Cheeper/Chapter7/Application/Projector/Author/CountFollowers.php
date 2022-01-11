<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Projector\Author;

//snippet count-followers
final class CountFollowers
{

    public static function ofAuthor(string $authorId): self
    {
        return new self($authorId);
    }

    private function __construct(
        private string $authorId
    ) {
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet

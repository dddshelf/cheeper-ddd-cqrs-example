<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler;

use Cheeper\Chapter5\Application\Query;

//snippet count-followers
final class CountFollowersQuery implements Query
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

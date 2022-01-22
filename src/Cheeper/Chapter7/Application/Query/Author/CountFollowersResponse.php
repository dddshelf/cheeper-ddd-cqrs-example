<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Query\Author;

//snippet count-followers-response
final class CountFollowersResponse
{
    public function __construct(
        public string $authorId,
        public string $authorUsername,
        public int $numberOfFollowers
    ) {
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function authorUsername(): string
    {
        return $this->authorUsername;
    }

    public function numberOfFollowers(): int
    {
        return $this->numberOfFollowers;
    }
}
//end-snippet

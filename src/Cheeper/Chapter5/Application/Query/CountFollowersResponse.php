<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query;

//snippet count-followers-response
final class CountFollowersResponse
{

    public function __construct(
        private string $authorId,
        private string $authorUsername,
        private int $numberOfFollowers
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

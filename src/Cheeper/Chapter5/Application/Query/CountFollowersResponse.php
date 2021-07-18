<?php

declare(strict_types=1);

namespace Architecture\CQRS\Application\Query;

//snippet count-followers-response
final class CountFollowersResponse
{
    private string $authorId;
    private string $authorUsername;
    private int $numberOfFollowers;

    public function __construct(
        string $authorId,
        string $authorUsername,
        int $numberOfFollowers
    )
    {
        $this->authorId = $authorId;
        $this->authorUsername = $authorUsername;
        $this->numberOfFollowers = $numberOfFollowers;
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

<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Application\Author\Command;

//snippet follow-command
final class FollowCommand
{
    public function __construct(
        private string $followeeUsername,
        private string $followedUsername
    ) {
    }

    //ignore
    public function followeeUsername(): string
    {
        return $this->followeeUsername;
    }

    public function followedUsername(): string
    {
        return $this->followedUsername;
    }
    //end-ignore

    public static function anAuthor(string $followed, string $followee): self
    {
        return new self($followee, $followed);
    }
}
//end-snippet

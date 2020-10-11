<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

//snippet follow-command
final class Follow
{
    private string $followeeUsername;
    private string $followedUsername;

    public function __construct(string $followeeUsername, string $followedUsername)
    {
        $this->followeeUsername = $followeeUsername;
        $this->followedUsername = $followedUsername;
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

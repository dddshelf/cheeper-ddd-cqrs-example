<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use JetBrains\PhpStorm\Pure;

//snippet follow-command
final class Follow
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

    #[Pure] public static function anAuthor(string $followed, string $followee): self
    {
        return new self($followee, $followed);
    }
}
//end-snippet

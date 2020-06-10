<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use Cheeper\Application\Command\SyncCommand;

final class Follow implements SyncCommand
{
    private string $followeeUsername;
    private string $followedUsername;

    public function __construct(string $followeeUsername, string $followedUsername)
    {
        $this->followeeUsername = $followeeUsername;
        $this->followedUsername = $followedUsername;
    }

    public function followeeUsername(): string
    {
        return $this->followeeUsername;
    }

    public function followedUsername(): string
    {
        return $this->followedUsername;
    }
}

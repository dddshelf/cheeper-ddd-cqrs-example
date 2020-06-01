<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use Cheeper\Application\Command\SyncCommand;
use Ramsey\Uuid\UuidInterface;

final class Follow implements SyncCommand
{
    private string $id;
    private string $followee;
    private string $followed;

    public function __construct(string $id, string $followee, string $followed)
    {
        $this->id = $id;
        $this->followee = $followee;
        $this->followed = $followed;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFollowee(): string
    {
        return $this->followee;
    }

    public function getFollowed(): string
    {
        return $this->followed;
    }
}

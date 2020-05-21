<?php

declare(strict_types=1);

namespace Cheeper\Application\Command\Author;

use Cheeper\Application\Command\SyncCommand;
use Ramsey\Uuid\UuidInterface;

final class Follow implements SyncCommand
{
    private UuidInterface $id;
    private string $followee;
    private string $followed;

    public function __construct(UuidInterface $id, string $followee, string $followed)
    {
        $this->id = $id;
        $this->followee = $followee;
        $this->followed = $followed;
    }

    public function getId(): UuidInterface
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

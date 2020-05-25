<?php

declare(strict_types=1);

namespace Architecture\CQRS\Application\Query;

use Ramsey\Uuid\UuidInterface;

//snippet count-followers
final class CountFollowers
{
    private UuidInterface $userId;

    private function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }

    public static function ofUser(UuidInterface $userId): self
    {
        return new self($userId);
    }

    public function userId(): UuidInterface
    {
        return $this->userId;
    }
}
//end-snippet

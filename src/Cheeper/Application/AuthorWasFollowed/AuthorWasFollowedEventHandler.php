<?php

declare(strict_types=1);

namespace Cheeper\Application\AuthorWasFollowed;

use Cheeper\DomainModel\Author\AuthorWasFollowed;
use Redis;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AuthorWasFollowedEventHandler
{
    public function __construct(
        private readonly Redis $redis,
    ) {
    }

    public function __invoke(AuthorWasFollowed $event): void
    {
        $this->redis->incr("followers_of:" . $event->toAuthorId);
    }
}

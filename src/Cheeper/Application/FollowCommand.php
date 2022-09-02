<?php

declare(strict_types=1);

namespace Cheeper\Application;

/** @psalm-immutable */
final class FollowCommand implements Command
{
    public function __construct(
        public readonly string $fromAuthorId,
        public readonly string $toAuthorId,
    ) {
    }
}
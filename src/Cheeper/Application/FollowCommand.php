<?php

declare(strict_types=1);

namespace Cheeper\Application;

/** @psalm-immutable */
final class FollowCommand implements Command
{
    /**
     * @psalm-param non-empty-string $fromAuthorId
     * @psalm-param non-empty-string $toAuthorId
     */
    public function __construct(
        public readonly string $fromAuthorId,
        public readonly string $toAuthorId,
    ) {
    }
}
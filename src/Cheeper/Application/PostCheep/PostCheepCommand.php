<?php

declare(strict_types=1);

namespace Cheeper\Application\PostCheep;

use Cheeper\Application\Command;

/** @psalm-immutable */
final class PostCheepCommand implements Command
{
    /**
     * @psalm-param non-empty-string $cheepId
     * @psalm-param non-empty-string $username
     * @psalm-param non-empty-string $message
     */
    public function __construct(
        public readonly string $cheepId,
        public readonly string $username,
        public readonly string $message,
    ) {
    }
}

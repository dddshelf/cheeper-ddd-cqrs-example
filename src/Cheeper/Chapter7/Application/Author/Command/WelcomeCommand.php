<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Command;

use Cheeper\Chapter7\Application\Command;
use Cheeper\Chapter7\Application\MessageTrait;

final class WelcomeCommand implements Command
{
    use MessageTrait;

    private function __construct(
        private string $authorId,
    ) {
        $this->stampAsNewMessage();
    }

    public static function ofAuthorId(string $authorId): self
    {
        return new self($authorId);
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}

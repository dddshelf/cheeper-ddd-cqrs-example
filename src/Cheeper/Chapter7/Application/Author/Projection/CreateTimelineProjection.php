<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\Application\Projection;

final class CreateTimelineProjection implements Projection
{
    use MessageTrait;

    public static function ofAuthor(string $authorId, string $authorUsername): self
    {
        return new self($authorId, $authorUsername);
    }

    private function __construct(
        private string $authorId,
        private string $authorUsername
    ) {
        $this->stampAsNewMessage();
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function authorUsername(): string
    {
        return $this->authorUsername;
    }
}

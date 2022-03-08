<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Application\Author\Query;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\Application\Query;

final class TimelineQuery implements Query
{
    use MessageTrait;

    public static function fromArray(array $array): self
    {
        return new self(
            (string) $array['author_id'],
            (int) $array['offset'],
            (int) $array['size'],
        );
    }

    private function __construct(
        private string $authorId,
        private int $offset,
        private int $size,
    ) {
        $this->stampAsNewMessage();
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function size(): int
    {
        return $this->size;
    }
}

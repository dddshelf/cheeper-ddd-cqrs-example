<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Query;

use Cheeper\Chapter7\Application\Query;

//snippet timeline-query
final class TimelineQuery implements Query
{
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
//end-snippet

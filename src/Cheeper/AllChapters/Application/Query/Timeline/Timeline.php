<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Application\Query\Timeline;

use Cheeper\Chapter6\Application\Query\Query;

//snippet timeline-query
final class Timeline implements Query
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

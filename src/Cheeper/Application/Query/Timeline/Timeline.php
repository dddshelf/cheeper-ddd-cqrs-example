<?php

declare(strict_types=1);

namespace Cheeper\Application\Query\Timeline;

use Cheeper\Chapter6\Application\Query\Query;

//snippet timeline-query
final class Timeline implements Query
{
    public static function fromArray(array $array): self
    {
        return new self(
            (string) $array['author_id'],
        );
    }

    private function __construct(
        private string $authorId
    ) {
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet

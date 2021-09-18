<?php

declare(strict_types=1);

namespace Cheeper\Application\Query;

//snippet timeline-query
final class Timeline
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

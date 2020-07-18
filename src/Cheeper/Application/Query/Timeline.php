<?php

declare(strict_types=1);

namespace Cheeper\Application\Query;

//snippet timeline-query
final class Timeline
{
    private string $authorId;

    public static function fromArray(array $array): self
    {
        return new static(
            (string) $array['author_id'],
        );
    }

    private function __construct(string $authorId)
    {
        $this->authorId = $authorId;
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet

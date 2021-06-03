<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query;

use Ramsey\Uuid\UuidInterface;

//snippet count-followers
final class CountFollowers
{
    private UuidInterface $authorId;

    private function __construct(UuidInterface $authorId)
    {
        $this->authorId = $authorId;
    }

    public static function ofAuthor(UuidInterface $authorId): self
    {
        return new self($authorId);
    }

    public function authorId(): UuidInterface
    {
        return $this->authorId;
    }
}
//end-snippet

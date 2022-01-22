<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Query\Author;

use Cheeper\Chapter5\Application\Query\Query;
use Cheeper\Chapter7\Application\MessageTrait;

//snippet count-followers
final class CountFollowers implements Query
{
    use MessageTrait;

    private string $authorId;

    public static function ofAuthor(string $authorId): self
    {
        return new self($authorId);
    }

    private function __construct(string $authorId)
    {
        $this->authorId = $authorId;

        $this->stampAsNewMessage();
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet

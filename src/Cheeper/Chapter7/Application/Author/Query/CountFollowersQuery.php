<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Query;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\Application\Query;

//snippet count-followers
final class CountFollowersQuery implements Query
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

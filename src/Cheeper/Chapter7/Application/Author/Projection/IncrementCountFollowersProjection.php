<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Projection;

use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\Application\Projection;

//snippet snippet
final class IncrementCountFollowersProjection implements Projection
{
    use MessageTrait;

    public static function ofAuthor(string $authorId): self
    {
        return new self($authorId);
    }

    private function __construct(
        private string $authorId
    ) {
        $this->stampAsNewMessage();
    }

    public function authorId(): string
    {
        return $this->authorId;
    }
}
//end-snippet

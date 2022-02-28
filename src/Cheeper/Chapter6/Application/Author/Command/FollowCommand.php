<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Author\Command;

use Cheeper\Chapter6\Application\Command;

//snippet follow-command
final class FollowCommand implements Command
{
    private function __construct(
        private string $fromAuthorId,
        private string $toAuthorId
    ) {
    }

    //ignore
    public function fromAuthorId(): string
    {
        return $this->fromAuthorId;
    }

    public function toAuthorId(): string
    {
        return $this->toAuthorId;
    }
    //end-ignore

    public static function fromAuthorIdToAuthorId(string $from, string $to): self
    {
        return new self($from, $to);
    }
}
//end-snippet

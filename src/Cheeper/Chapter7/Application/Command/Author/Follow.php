<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Command\Author;

use Cheeper\Chapter7\Application\MessageTrait;

final class Follow
{
    use MessageTrait;

    private function __construct(
        private string $fromAuthorId,
        private string $toAuthorId
    ) {
    }

    public function fromAuthorId(): string
    {
        return $this->fromAuthorId;
    }

    public function toAuthorId(): string
    {
        return $this->toAuthorId;
    }

    public static function fromAuthorIdToAuthorId(string $from, string $to): self
    {
        return new self($from, $to);
    }
}

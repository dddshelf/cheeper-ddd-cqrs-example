<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Command;

use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\Chapter7\Application\Command;
use Cheeper\Chapter7\Application\MessageTrait;

final class FollowCommand implements Command
{
    use MessageTrait;

    private function __construct(
        private string $followId,
        private string $fromAuthorId,
        private string $toAuthorId
    ) {
        $this->stampAsNewMessage();
    }

    public function fromAuthorId(): string
    {
        return $this->fromAuthorId;
    }

    public function toAuthorId(): string
    {
        return $this->toAuthorId;
    }

    public function followId(): string
    {
        return $this->followId;
    }

    public static function fromAuthorIdToAuthorId(string $from, string $to): self
    {
        return new self($from, $to, FollowId::nextIdentity()->toString());
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['follow_id'] ?? '',
            $array['from_author_id'] ?? '',
            $array['to_author_id'] ?? '',
        );
    }
}

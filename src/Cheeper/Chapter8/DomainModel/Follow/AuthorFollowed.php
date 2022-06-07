<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use DateTimeImmutable;
use DateTimeZone;

final class AuthorFollowed implements DomainEvent
{
    use MessageTrait;

    private function __construct(
        private string $followId,
        private string $fromAuthorId,
        private string $toAuthorId,
        private DateTimeImmutable $occurredOn
    ) {
        $this->stampAsNewMessage();
    }

    public static function fromFollow(Follow $follow): self
    {
        return new self(
            $follow->followId()->toString(),
            $follow->fromAuthorId()->toString(),
            $follow->toAuthorId()->toString(),
            Clock::instance()
                ->now()
                ->setTimezone(new \DateTimeZone('UTC'))
        );
    }

    public function followId(): string
    {
        return $this->followId;
    }

    public function fromAuthorId(): string
    {
        return $this->fromAuthorId;
    }

    public function toAuthorId(): string
    {
        return $this->toAuthorId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter7\Application\MessageTrait;
use Cheeper\Chapter7\DomainModel\DomainEvent;
use DateTimeImmutable;

// snippet cheep-posted-domain-event
final class CheepPosted implements DomainEvent
{
    use MessageTrait;

    private function __construct(
        private string $cheepId,
        private string $authorId,
        private string $cheepMessage,
        private string $cheepDate,
        private DateTimeImmutable $occurredOn
    ) {
        $this->stampAsNewMessage();
    }

    public static function fromCheep(Cheep $cheep): self
    {
        return new self(
            $cheep->cheepId()->toString(),
            $cheep->authorId()->toString(),
            $cheep->cheepMessage()->message(),
            $cheep->cheepDate()->date(),
            Clock::instance()->now()
        );
    }

    public static function create(
        CheepId $cheepId,
        AuthorId $authorId,
        CheepMessage $cheepMessage,
        CheepDate $cheepDate,
    ): self {
        return new self(
            $cheepId->toString(),
            $authorId->toString(),
            $cheepMessage->message(),
            $cheepDate->date(),
            Clock::instance()->now()
        );
    }

    public function cheepId(): string
    {
        return $this->cheepId;
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function cheepMessage(): string
    {
        return $this->cheepMessage;
    }

    public function cheepDate(): string
    {
        return $this->cheepDate;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

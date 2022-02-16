<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\Chapter4\DomainModel\DomainEvent;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

// snippet cheep-posted-domain-event
final class CheepPosted implements DomainEvent
{
    private function __construct(
        private string $cheepId,
        private string $authorId,
        private string $cheepMessage,
        private string $cheepDate,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromCheep(Cheep $cheep): self
    {
        return new self(
            $cheep->id()->toString(),
            $cheep->authorId()->toString(),
            $cheep->message()->message(),
            $cheep->date()->format(DateTimeInterface::ATOM),
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

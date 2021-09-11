<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\DomainEvent;
use DateTimeImmutable;
use DateTimeZone;

// snippet cheep-posted-domain-event
final class CheepPosted implements DomainEvent
{
    private function __construct(
        private string $cheepId,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public static function fromCheep(Cheep $cheep): self
    {
        return new self(
            $cheep->cheepId()->toString(),
            new DateTimeImmutable(
                timezone: new DateTimeZone("UTC")
            )
        );
    }

    public function cheepId(): string
    {
        return $this->cheepId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
// end-snippet

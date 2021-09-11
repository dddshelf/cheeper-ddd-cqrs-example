<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\TriggerEventsTrait;
use Safe\DateTimeImmutable;

class Cheep
{
    use TriggerEventsTrait;

    private function __construct(
        private AuthorId $authorId,
        private CheepId $cheepId,
        private CheepMessage $cheepMessage,
        private CheepDate $cheepDate,
    ) {
        $this->notifyDomainEvent(
            CheepPosted::fromCheep($this)
        );
    }

    public static function compose(AuthorId $authorId, CheepId $cheepId, CheepMessage $cheepMessage): self
    {
        $cheepDate = new CheepDate(
            (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d')
        );

        return new self(
            $authorId,
            $cheepId,
            $cheepMessage,
            $cheepDate
        );
    }

    final public function authorId(): AuthorId
    {
        return $this->authorId;
    }

    final public function cheepId(): CheepId
    {
        return $this->cheepId;
    }

    final public function cheepMessage(): CheepMessage
    {
        return $this->cheepMessage;
    }

    final public function cheepDate(): CheepDate
    {
        return $this->cheepDate;
    }

    final public function recomposeWith(CheepMessage $cheepMessage): void
    {
        $this->cheepMessage = $cheepMessage;
    }
}

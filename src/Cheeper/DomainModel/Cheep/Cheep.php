<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\AggregateRoot;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Clock\Clock;

/**
 * @final
 * @extends AggregateRoot<CheepWasPosted>
 */
class Cheep extends AggregateRoot
{
    /**
     * @psalm-param non-empty-string $authorId
     * @psalm-param non-empty-string $cheepId
     */
    private function __construct(
        private string $authorId,
        private string $cheepId,
        private CheepMessage $cheepMessage,
        private CheepDate $cheepDate,
    ) {
    }

    public static function compose(AuthorId $authorId, CheepId $cheepId, CheepMessage $cheepMessage): self
    {
        $now = Clock::instance()
            ->now()
            ->setTimezone(new \DateTimeZone('UTC'))
        ;

        $cheepDate = new CheepDate(
            $now->format('Y-m-d H:i:s')
        );

        $cheep = new self(
            $authorId->id,
            $cheepId->id,
            $cheepMessage,
            $cheepDate
        );

        $cheep->recordThat(
            new CheepWasPosted(
                $authorId->id,
                $cheepId->id,
                $cheepMessage->message,
                $now,
                $now
            )
        );

        return $cheep;
    }

    final public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    final public function cheepId(): CheepId
    {
        return CheepId::fromString($this->cheepId);
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

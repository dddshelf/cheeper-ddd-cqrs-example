<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Author\AuthorId;
use Safe\DateTimeImmutable;

class Cheep
{
    private function __construct(
        private AuthorId $authorId,
        private CheepId $cheepId,
        private CheepMessage $cheepMessage,
        private CheepDate $cheepDate,
    ) { }

    public static function compose(AuthorId $authorId, CheepId $cheepId, CheepMessage $cheepMessage): self
    {
        return new self(
            $authorId,
            $cheepId,
            $cheepMessage,
            new CheepDate(
                (new DateTimeImmutable('now'))->format('Y-m-d')
            )
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

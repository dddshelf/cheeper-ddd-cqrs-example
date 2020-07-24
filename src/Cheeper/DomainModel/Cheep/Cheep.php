<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Author\AuthorId;
use Safe\DateTimeImmutable;

class Cheep
{
    private AuthorId $authorId;
    private CheepId $cheepId;
    private CheepMessage $cheepMessage;
    private CheepDate $cheepDate;

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

    private function __construct(AuthorId $authorId, CheepId $cheepId, CheepMessage $cheepMessage, CheepDate $cheepDate)
    {
        $this->authorId = $authorId;
        $this->cheepId = $cheepId;
        $this->cheepMessage = $cheepMessage;
        $this->cheepDate = $cheepDate;
    }

    public function authorId(): AuthorId
    {
        return $this->authorId;
    }

    public function cheepId(): CheepId
    {
        return $this->cheepId;
    }

    public function cheepMessage(): CheepMessage
    {
        return $this->cheepMessage;
    }

    public function cheepDate(): CheepDate
    {
        return $this->cheepDate;
    }

    public function recomposeWith(CheepMessage $cheepMessage): void
    {
        $this->cheepMessage = $cheepMessage;
    }
}

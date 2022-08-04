<?php

declare(strict_types=1);

namespace App\Dto;

use Cheeper\DomainModel\Cheep\Cheep;
use DateTimeInterface;

final class CheepDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $authorId,
        public readonly string $text,
        public readonly DateTimeInterface $createdAt,
    )
    {
    }

    public static function assembleFrom(Cheep $cheep): self
    {
        return new self(
            $cheep->cheepId()->toString(),
            $cheep->authorId()->toString(),
            $cheep->cheepMessage()->message(),
            \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $cheep->cheepDate()->date())
        );
    }
}
<?php

declare(strict_types=1);

namespace App\Dto;

use Cheeper\DomainModel\Cheep\Cheep;
use DateTimeInterface;
use Safe\DateTimeImmutable;

final class CheepDto
{
    /**
     * @psalm-param non-empty-string $id
     * @psalm-param non-empty-string $authorId
     * @psalm-param non-empty-string $text
     */
    public function __construct(
        public readonly string $id,
        public readonly string $authorId,
        public readonly string $text,
        public readonly string $createdAt,
    ) {
    }

    public static function assembleFrom(Cheep $cheep): self
    {
        return new self(
            $cheep->cheepId()->id,
            $cheep->authorId()->id,
            $cheep->cheepMessage()->message,
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $cheep->cheepDate()->date())->format(DateTimeInterface::ATOM)
        );
    }
}

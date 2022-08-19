<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Author\Author;
use DateTimeInterface;

interface CheepRepository
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): Cheep|null;

    /** @return list<Cheep> */
    public function all(): array;

    /** @return list<Cheep> */
    public function ofFollowingPeopleOf(Author $author, int $offset, int $size): array;
}

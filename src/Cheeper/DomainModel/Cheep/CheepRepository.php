<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Author\Author;
use DateTimeInterface;

interface CheepRepository
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): ?Cheep;

    /** @return Cheep[] */
    public function all(): array;

    /** @return Cheep[] */
    public function allBetween(DateTimeInterface $from, DateTimeInterface $to): array;

    /** @return Cheep[] */
    public function allGroupedByMonthAndYear(): array;

    /** @return Cheep[] */
    public function ofFollowersOfAuthor(Author $author): array;

    /** @return Cheep[] */
    public function groupedByMonth(int $year): array;

    /** @return Cheep[] */
    public function ofFollowingPeopleOf(Author $author, int $offset, int $size): array;
}

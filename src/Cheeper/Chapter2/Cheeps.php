<?php

declare(strict_types=1);

namespace Cheeper\Chapter2;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use DateTimeInterface;

//snippet cheeps-with-a-lot-of-finders
interface Cheeps
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
}
//end-snippet

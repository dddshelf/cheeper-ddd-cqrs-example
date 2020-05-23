<?php

declare(strict_types=1);

namespace CheeperHexagonal;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;

//snippet cheeps-with-a-lot-of-finders
interface Cheeps
{
    public function add(Cheep $cheep): void;
    
    public function ofId(CheepId $cheepId): ?Cheep;

    /** @return Cheep[] */
    public function all(): array;

    /** @return Cheep[] */
    public function allBetween(
        \DateTimeInterface $dateTime1,
        \DateTimeInterface $dateTime2
    ): array;

    /** @return Cheep[] */
    public function allGroupedByMonthAndYear(): array;

    /** @return Cheep[] */
    public function ofFollowersOfAuthor(Author $author): array;

    /** @return Cheep[] */
    public function groupedByMonth(int $year): array;
}
//end-snippet

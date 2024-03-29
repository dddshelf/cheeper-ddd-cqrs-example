<?php

declare(strict_types=1);

namespace Cheeper\Chapter2\Hexagonal\DomainModel\CheepRepositoryWithLotsOfFinders;

use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter2\Author;
use Cheeper\Chapter2\Cheep;
use DateTimeInterface;

//snippet snippet
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
}
//end-snippet

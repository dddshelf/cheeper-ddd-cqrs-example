<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Cheep\CheepId;

//snippet snippet
interface CheepRepository
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): ?Cheep;
}
//end-snippet

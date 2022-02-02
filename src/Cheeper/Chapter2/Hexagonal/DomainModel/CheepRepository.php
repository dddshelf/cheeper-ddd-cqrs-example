<?php

declare(strict_types=1);

namespace Cheeper\Chapter2\Hexagonal\DomainModel;

use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter2\Cheep;

//snippet snippet
interface CheepRepository
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): ?Cheep;
}
//end-snippet

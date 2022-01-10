<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Cheep;

use Cheeper\DomainModel\Cheep\CheepId;

//snippet cheeps
interface Cheeps
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): ?Cheep;
}
//end-snippet

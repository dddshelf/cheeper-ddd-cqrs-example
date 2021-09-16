<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Cheep;

use Cheeper\DomainModel\Author\Author;

//snippet cheeps
interface Cheeps
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): ?Cheep;
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Cheep;

//snippet cheeps
interface Cheeps
{
    public function add(Cheep $cheep): void;
    public function ofId(CheepId $cheepId): ?Cheep;
}
//end-snippet

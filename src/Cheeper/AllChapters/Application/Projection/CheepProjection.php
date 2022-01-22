<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Application\Projection;

use Cheeper\AllChapters\DomainModel\Cheep\CheepPosted;

//snippet cheep-projection-interface
interface CheepProjection
{
    public function whenCheepPosted(CheepPosted $event): void;
}
//end-snippet
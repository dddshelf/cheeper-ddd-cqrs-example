<?php

declare(strict_types=1);

namespace Cheeper\Application\Projection;

use Cheeper\DomainModel\Cheep\CheepPosted;

interface CheepProjection
{
    public function whenCheepPosted(CheepPosted $event): void;
}
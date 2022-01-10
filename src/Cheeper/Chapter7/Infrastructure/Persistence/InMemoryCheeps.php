<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Persistence;

use Cheeper\Chapter7\DomainModel\Cheep\Cheep;
use Cheeper\Chapter7\DomainModel\Cheep\Cheeps;
use Cheeper\DomainModel\Cheep\CheepId;

//snippet inmemory-cheeps
final class InMemoryCheeps implements Cheeps
{
    /** @var Cheep[] */
    private array $items = [];

    public function add(Cheep $cheep): void
    {
        $this->items[$cheep->cheepId()->toString()] = $cheep;
    }

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->items[$cheepId->toString()] ?? null;
    }
}
//end-snippet

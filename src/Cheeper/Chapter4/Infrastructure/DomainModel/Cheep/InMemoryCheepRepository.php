<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Infrastructure\DomainModel\Cheep;

use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter4\DomainModel\Cheep\Cheep;
use Cheeper\Chapter4\DomainModel\Cheep\CheepRepository;

//snippet inmemory-cheeps
final class InMemoryCheepRepository implements CheepRepository
{
    /** @var Cheep[] */
    private array $items = [];

    public function add(Cheep $cheep): void
    {
        $this->items[$cheep->id()->toString()] = $cheep;
    }

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->items[$cheepId->toString()] ?? null;
    }
}
//end-snippet

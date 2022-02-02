<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Infrastructure\Persistence;

use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter2\Cheep;
use Cheeper\Chapter2\Hexagonal\DomainModel\CheepRepository;

//snippet inmemory-cheeps
final class InMemoryCheepRepository implements CheepRepository
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

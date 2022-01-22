<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\Infrastructure\Persistence;

use Cheeper\AllChapters\DomainModel\Author\Author;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\Cheep;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\Cheeps;
use function Functional\filter;
use function Functional\select;

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

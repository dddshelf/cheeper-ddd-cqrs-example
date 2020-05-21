<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\Cheeps;
use function Functional\filter;
use function Functional\select;

final class InMemoryCheeps implements Cheeps
{
    /** @var Cheep[] */
    private array $items = [];

    public function add(Cheep $cheep): void
    {
        $this->items[$cheep->cheepId()->toString()] = $cheep;
    }

    public function ofFollowersOfAuthor(Author $author): array
    {
        return select(
            $this->items,
            fn (Cheep $cheep) => count(
                filter(
                    $author->following(),
                    fn (AuthorId $aid) => $aid->equals($cheep->authorId())
                )
            ) > 0
        );
    }

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->items[$cheepId->toString()] ?? null;
    }
}

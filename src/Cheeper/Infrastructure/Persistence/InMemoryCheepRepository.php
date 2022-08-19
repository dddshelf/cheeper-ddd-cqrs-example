<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Psl\Iter;

final class InMemoryCheepRepository implements CheepRepository
{
    /** @var list<Cheep> */
    private array $cheeps = [];

    public function add(Cheep $cheep): void
    {
        $this->cheeps[] = $cheep;
    }

    public function ofId(CheepId $cheepId): Cheep|null
    {
        return Iter\search($this->cheeps, fn(Cheep $c) => $c->cheepId()->equals($cheepId));
    }

    public function all(): array
    {
        return $this->cheeps;
    }

    public function ofFollowingPeopleOf(Author $author, int $offset, int $size): array
    {
        return $this->all();
    }
}
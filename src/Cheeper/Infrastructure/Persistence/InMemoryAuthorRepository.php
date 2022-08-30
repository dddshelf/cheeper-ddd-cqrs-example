<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\UserName;
use Psl\Iter;
use Psl\Vec;

final class InMemoryAuthorRepository implements AuthorRepository
{
    /** @var list<Author> */
    private array $authors = [];

    public function ofId(AuthorId $authorId): Author|null
    {
        return Iter\search($this->authors, static fn (Author $a) => $a->authorId()->equals($authorId));
    }

    public function ofUserName(UserName $userName): Author|null
    {
        return Iter\first(
            Vec\filter($this->authors, static fn (Author $a) => $a->userName()->equalsTo($userName))
        );
    }

    public function add(Author $author): void
    {
        $this->authors[] = $author;
    }

    public function all(): array
    {
        return $this->authors;
    }
}

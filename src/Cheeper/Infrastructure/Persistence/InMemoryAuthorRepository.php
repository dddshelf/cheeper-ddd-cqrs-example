<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\UserName;

final class InMemoryAuthorRepository implements AuthorRepository
{
    /** @var Author[] */
    private array $authors = [];

    public function ofId(AuthorId $authorId): ?Author
    {
        return $this->authors[$authorId->toString()] ?? null;
    }

    public function ofUserName(UserName $userName): ?Author
    {
        $candidates = array_filter($this->authors, static fn(Author $a) => $a->userName()->equalsTo($userName));

        if (count($candidates) === 0) {
            return null;
        }

        return array_shift($candidates);
    }

    public function add(Author $author): void
    {
        $this->authors[$author->authorId()->toString()] = $author;
    }
}
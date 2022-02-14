<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Infrastructure\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter4\DomainModel\Author\Author;
use Cheeper\Chapter4\DomainModel\Author\AuthorRepository;
use function Functional\head;
use function Functional\select;

final class InMemoryAuthorRepository implements AuthorRepository
{
    public array $authors;

    public function __construct()
    {
        $this->authors = [];
    }

    public function ofId(AuthorId $authorId): ?Author
    {
        return $this->authors[$authorId->id()] ?? null;

        $candidate = head(
            select($this->authors, fn (Author $u): bool => $u->authorId()->equals($authorId))
        );

        return $candidate ?? null;
    }

    public function ofUserName(UserName $userName): ?Author
    {
        $candidate = head(
            select($this->authors, fn (Author $u): bool => $u->userName()->equalsTo($userName))
        );

        if (null === $candidate) {
            return null;
        }

        return $candidate;
    }

    public function add(Author $author): void
    {
        $candidate = head(
            select($this->authors, fn (Author $u): bool => $u->authorId()->equals($author->authorId()))
        );

        if ((null !== $candidate && $candidate !== $author) || null === $candidate) {
            $this->authors[$author->authorId()->toString()] = $author;
        }
    }
}

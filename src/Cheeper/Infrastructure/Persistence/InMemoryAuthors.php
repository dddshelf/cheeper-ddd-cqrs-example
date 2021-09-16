<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\UserName;
use function Functional\head;
use function Functional\select;

final class InMemoryAuthors implements Authors
{
    /** @var array<string, Author> */
    public array $authors;

    public function __construct()
    {
        $this->authors = [];
    }

    public function ofId(AuthorId $authorId): ?Author
    {
        $candidate = head(
            select($this->authors, fn (Author $u): bool => $u->authorId()->equals($authorId))
        );

        if (null === $candidate) {
            return $candidate;
        }

        return clone $candidate;
    }

    public function ofUserName(UserName $userName): ?Author
    {
        $candidate = head(
            select($this->authors, fn (Author $u): bool => $u->userName()->equalsTo($userName))
        );

        if (null === $candidate) {
            return null;
        }

        return clone $candidate;
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

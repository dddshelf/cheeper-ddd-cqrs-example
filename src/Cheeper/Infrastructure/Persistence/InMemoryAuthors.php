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
    private array $authors = [];

    public function ofId(AuthorId $authorId): ?Author
    {
        $candidate = head(
            select($this->authors, fn(Author $u): bool => $u->userId()->equals($authorId))
        );

        if (null === $candidate) {
            return null;
        }

        return clone $candidate;
    }

    public function ofUserName(UserName $userName): ?Author
    {
        $candidate = head(
            select($this->authors, fn(Author $u): bool => $u->userName()->equalsTo($userName))
        );

        if (null === $candidate) {
            return null;
        }

        return clone $candidate;
    }

    public function save(Author $author): void
    {
        $candidate = head(
            select($this->authors, fn (Author $u): bool => $u->userId()->equals($author->userId()))
        );

        if ((null !== $candidate && $candidate != $author) || null === $candidate) {
            $this->authors[$author->userId()->toString()] = $author;
        }
    }
}

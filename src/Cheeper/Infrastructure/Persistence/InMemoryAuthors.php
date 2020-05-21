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
    /** @var Author[] */
    private array $authors = [];

    public function ofId(AuthorId $authorId): ?Author
    {
        return head(
            select($this->authors, fn (Author $u): bool => $u->userId()->equals($authorId))
        );
    }

    public function ofUserName(UserName $userName): ?Author
    {
        return head(
            select($this->authors, fn (Author $u): bool => $u->userName()->equalsTo($userName))
        );
    }

    public function add(Author $author): void
    {
        $this->authors[] = $author;
    }
}

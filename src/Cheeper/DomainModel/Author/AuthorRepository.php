<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

interface AuthorRepository
{
    public function ofId(AuthorId $authorId): Author|null;
    public function ofUserName(UserName $userName): Author|null;
    public function add(Author $author): void;
    /** @return list<Author> */
    public function all(): array;
}

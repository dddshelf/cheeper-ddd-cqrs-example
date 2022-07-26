<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Author;

interface AuthorRepository
{
    public function ofId(AuthorId $authorId): ?Author;
    public function ofUserName(UserName $userName): ?Author;
    public function add(Author $author): void;
}

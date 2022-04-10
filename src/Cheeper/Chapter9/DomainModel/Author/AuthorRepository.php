<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;

//snippet authors
interface AuthorRepository
{
    public function ofId(AuthorId $authorId): ?Author;
    public function add(Author $author): void;
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Chapter2\Hexagonal\DomainModel;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter2\Author;

//snippet authors
interface AuthorRepository
{
    public function ofId(AuthorId $authorId): ?Author;
    public function ofUserName(UserName $userName): ?Author;
    public function add(Author $author): void;
}
//end-snippet

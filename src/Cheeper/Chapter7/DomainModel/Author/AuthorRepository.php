<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;

//snippet authors
interface AuthorRepository
{
    public function ofId(AuthorId $authorId): Author|null;
    public function ofUserName(UserName $userName): Author|null;
    public function add(Author $author): void;
}
//end-snippet

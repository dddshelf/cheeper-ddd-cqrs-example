<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\DomainModel\Author;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\UserName;

//snippet authors
interface Authors
{
    public function ofId(AuthorId $authorId): ?Author;
    public function ofUserName(UserName $userName): ?Author;
    public function add(Author $author): void;
}
//end-snippet

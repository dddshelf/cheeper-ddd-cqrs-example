<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\UserName;

interface AuthorRepository
{
    public function ofId(AuthorId $authorId): Author|null;
    public function ofUserName(UserName $userName): Author|null;
    public function add(Author $author): void;
}

<?php

declare(strict_types=1);

namespace Cheeper\AllChapters\DomainModel\Author;

//snippet authors
interface Authors
{
    public function ofId(AuthorId $authorId): ?Author;
    public function ofUserName(UserName $userName): ?Author;
    public function add(Author $author): void;
}
//end-snippet

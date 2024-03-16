<?php

declare(strict_types=1);

namespace Cheeper\Chapter9\DomainModel\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;

interface AuthorsWithMoreThanAThousandOfFollowers
{
    /** @return AuthorId[] */
    public function __invoke(): array;
}
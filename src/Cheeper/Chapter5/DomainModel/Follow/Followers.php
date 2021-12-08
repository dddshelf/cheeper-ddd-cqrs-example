<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\DomainModel\Follow;

use Cheeper\DomainModel\Author\AuthorId;

interface Followers
{
    public function ofAuthorId(AuthorId $authorId): ?NumberOfFollowers;
}

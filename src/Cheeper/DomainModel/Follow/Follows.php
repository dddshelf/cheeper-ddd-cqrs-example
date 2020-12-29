<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

//snippet follows
use Cheeper\DomainModel\Author\AuthorId;

interface Follows
{
    public function numberOfFollowersFor(AuthorId $authorId): int;
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\DomainModel\Follow;

use Cheeper\DomainModel\Author\AuthorId;

//snippet follows
interface Follows
{
    public function numberOfFollowersFor(AuthorId $authorId): int;
}
//end-snippet

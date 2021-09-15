<?php

declare(strict_types=1);

namespace Architecture\CQRS\App\Repository;

use Architecture\CQRS\App\Entity\Followers;
use Cheeper\DomainModel\Author\AuthorId;

interface FollowersRepository
{
    public function ofAuthorId(AuthorId $authorId): ?Followers;
}

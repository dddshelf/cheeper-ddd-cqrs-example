<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Infrastructure\Persistence;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter5\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter5\DomainModel\Follow\NumberOfFollowers;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrmFollowRepository implements FollowRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function ofAuthorId(AuthorId $authorId): ?NumberOfFollowers
    {
        return null;
    }
}

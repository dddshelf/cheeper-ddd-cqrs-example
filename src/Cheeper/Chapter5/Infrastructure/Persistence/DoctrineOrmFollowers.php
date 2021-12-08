<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Infrastructure\Persistence;

use Cheeper\Chapter5\DomainModel\Follow\Followers;
use Cheeper\Chapter5\DomainModel\Follow\NumberOfFollowers;
use Cheeper\DomainModel\Author\AuthorId;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrmFollowers implements Followers
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function ofAuthorId(AuthorId $authorId): ?NumberOfFollowers
    {
        // TODO: Implement ofAuthorId() method.
    }
}
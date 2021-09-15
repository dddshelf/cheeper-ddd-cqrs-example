<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine;

use Architecture\CQRS\App\Entity\Followers;
use Architecture\CQRS\App\Repository\FollowersRepository;
use Cheeper\DomainModel\Author\AuthorId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<Followers>
 */
final class DoctrineFollowersRepository extends ServiceEntityRepository implements FollowersRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Followers::class);
    }

    public function ofAuthorId(AuthorId $authorId): ?Followers
    {
        return $this->find($authorId->toString());
    }
}

<?php

declare(strict_types=1);

namespace Architecture\CQRS\Infrastructure\Persistence\Doctrine;

use Architecture\CQRS\App\Entity\Followers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<Followers>
 */
final class DoctrineFollowersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Followers::class);
    }

    public function ofAuthorId(UuidInterface $authorId): ?Followers
    {
        return $this->find($authorId->toString());
    }
}

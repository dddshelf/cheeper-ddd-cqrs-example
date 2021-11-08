<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\Follows;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

//snippet doctrine-orm-follows
final class DoctrineOrmFollows implements Follows
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return 13;
    }

    public function add(Follow $follow): void
    {
        $this->em->persist($follow);
    }

    public function ofFromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow
    {
        $repository = $this->em->getRepository(Follow::class);

        return $repository->findOneBy([
            'fromAuthorId' => $fromAuthorId,
            'toAuthorId' => $toAuthorId,
        ]);
    }

    public function toAuthorId(AuthorId $authorId): array
    {
        $repository = $this->em->getRepository(Follow::class);

        return $repository->findBy(['toAuthorId' => $authorId]);
    }
}
//end-snippet

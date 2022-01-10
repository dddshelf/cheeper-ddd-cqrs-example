<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Persistence;

use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\Chapter7\DomainModel\Follow\Follows;
use Cheeper\DomainModel\Author\AuthorId;
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

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\Chapter7\DomainModel\Follow\FollowRepository;
use Doctrine\ORM\EntityManagerInterface;

//snippet doctrine-orm-follows
final class DoctrineOrmFollowRepository implements FollowRepository
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

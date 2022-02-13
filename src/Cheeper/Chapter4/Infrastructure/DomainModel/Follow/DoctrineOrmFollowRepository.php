<?php

declare(strict_types=1);

namespace Cheeper\Chapter4\Infrastructure\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter4\DomainModel\Follow\Follow;
use Cheeper\Chapter4\DomainModel\Follow\FollowRepository;
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
        return $this->em
            ->getRepository(Follow::class)->findOneBy([
                'fromAuthorId' => $fromAuthorId,
                'toAuthorId' => $toAuthorId,
            ]);
    }

    public function toAuthorId(AuthorId $authorId): array
    {
        return $this->em
            ->getRepository(Follow::class)
            ->findBy(['toAuthorId' => $authorId]);
    }
}
//end-snippet

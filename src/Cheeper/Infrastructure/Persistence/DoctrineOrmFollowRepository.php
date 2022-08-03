<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrmFollowRepository implements FollowRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return count($this->em->getRepository(Follow::class)->findBy(['toAuthorId' => $authorId]));
    }

    public function add(Follow $follow): void
    {
        $this->em->persist($follow);
        $this->em->flush();
    }

    public function fromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow
    {
        return $this->em->getRepository(Follow::class)->findOneBy([
            'fromAuthorId' => $fromAuthorId,
            'toAuthorId' => $toAuthorId,
        ]);
    }

    public function toAuthorId(AuthorId $authorId): array
    {
        return $this->em->getRepository(Follow::class)->findBy(['toAuthorId' => $authorId]);
    }
}
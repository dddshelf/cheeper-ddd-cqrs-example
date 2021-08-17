<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\Follows;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

//snippet doctrine-orm-follows
final class DoctrineOrmFollows implements Follows
{
    private ObjectRepository $repository;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $this->repository = $em->getRepository(Follow::class);
    }

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return 13;
    }

    public function save(Follow $follow): void
    {
        $this->em->persist($follow);
        $this->em->flush();
    }

    public function ofFromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow
    {
        return $this->repository->findOneBy([
            'fromAuthorId.id' => $fromAuthorId,
            'toAuthorId.id' => $toAuthorId
        ]);
    }
}
//end-snippet

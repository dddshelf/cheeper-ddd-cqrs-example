<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\UserName;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

//snippet doctrine-orm-authors
final class DoctrineOrmAuthors implements Authors
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function ofId(AuthorId $authorId): ?Author
    {
        return $this->em
            ->getRepository(Author::class)
            ->findOneBy([
                'authorId.id' => Uuid::fromString($authorId->id()),
            ]);
    }

    public function ofUserName(UserName $userName): ?Author
    {
        return $this->em
            ->getRepository(Author::class)
            ->findOneBy(['userName.userName' => $userName->userName()]);
    }

    public function add(Author $author): void
    {
        $this->em->persist($author);
    }
}
//end-snippet

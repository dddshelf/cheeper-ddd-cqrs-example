<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\UserName;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrmAuthorRepository implements AuthorRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function ofId(AuthorId $authorId): ?Author
    {
        return $this->em
            ->getRepository(Author::class)
            ->findOneBy([
                'authorId' => $authorId->id(),
            ]);
    }

    public function ofUserName(UserName $userName): ?Author
    {
        return $this->em
            ->getRepository(Author::class)
            ->findOneBy(['userName' => $userName->userName()]);
    }

    public function add(Author $author): void
    {
        $this->em->persist($author);
        $this->em->flush();
    }

    public function all(): array
    {
        return $this->em
            ->getRepository(Author::class)
            ->findAll();
    }
}

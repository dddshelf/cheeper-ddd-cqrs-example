<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

final class DoctrineOrmCheepRepository implements CheepRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function add(Cheep $cheep): void
    {
        $this->em->persist($cheep);
        $this->em->flush();
    }

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->em->find(Cheep::class, Uuid::fromString($cheepId->id()));
    }

    public function all(): array
    {
        return $this->em->getRepository(Cheep::class)->findAll();
    }

    public function ofFollowingPeopleOf(Author $author, int $offset, int $size): array
    {
        $dql = <<<DQL
SELECT c
FROM Cheeper\DomainModel\Cheep\Cheep c
    JOIN Cheeper\DomainModel\Follow\Follow f WITH f.toAuthorId = c.authorId
WHERE f.fromAuthorId = :fromAuthorId
ORDER BY c.cheepDate.date DESC

DQL;

        $query = $this->em->createQuery($dql);
        $query->setFirstResult($offset);
        $query->setMaxResults($size);

        return $query->execute([
            'fromAuthorId' => $author->authorId()
        ]);
    }
}

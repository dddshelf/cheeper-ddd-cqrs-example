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
    }

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->em->find(Cheep::class, Uuid::fromString($cheepId->id()));
    }

    public function all(): array
    {
        return $this->em->getRepository(Cheep::class)->findAll();
    }

    public function allBetween(DateTimeInterface $from, DateTimeInterface $to): array
    {
        // TODO: Implement allBetween() method.
    }

    public function allGroupedByMonthAndYear(): array
    {
        // TODO: Implement allGroupedByMonthAndYear() method.
    }

    public function ofFollowersOfAuthor(Author $author): array
    {
        // TODO: Implement ofFollowersOfAuthor() method.
    }

    public function groupedByMonth(int $year): array
    {
        // TODO: Implement groupedByMonth() method.
    }
}

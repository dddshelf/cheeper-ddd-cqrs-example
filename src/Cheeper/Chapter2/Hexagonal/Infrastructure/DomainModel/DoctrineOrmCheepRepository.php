<?php

declare(strict_types=1);

namespace Cheeper\Chapter2\Hexagonal\Infrastructure\DomainModel;

use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter2\Cheep;
use Cheeper\Chapter2\Hexagonal\DomainModel\CheepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

//snippet doctrine-orm-cheeps
final class DoctrineOrmCheepRepository implements CheepRepository
{
    //ignore
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function add(Cheep $cheep): void
    {
        $this->em->persist($cheep);
    }
    //end-ignore

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->em->find(Cheep::class, Uuid::fromString($cheepId->id()));
    }
}
//end-snippet

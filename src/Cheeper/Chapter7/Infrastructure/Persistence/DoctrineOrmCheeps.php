<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Persistence;

use Cheeper\Chapter7\DomainModel\Cheep\Cheep;
use Cheeper\Chapter7\DomainModel\Cheep\Cheeps;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

//snippet doctrine-orm-cheeps
final class DoctrineOrmCheeps implements Cheeps
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

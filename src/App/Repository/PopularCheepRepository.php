<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PopularCheep;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PopularCheepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PopularCheep::class);
    }

    /**
     * @param Follow[] $follows
     * @return PopularCheep[]
     */
    public function of(array $follows): array
    {
        return $this->findBy([
            'id' => array_map(static fn (Follow $f) => $f->toAuthorId()->id(), $follows),
        ]);
    }
}

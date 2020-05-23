<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\Cheeps;
use Doctrine\ORM\EntityManagerInterface;

//snippet doctrine-orm-cheeps
final class DoctrineOrmCheeps implements Cheeps
{
    //ignore
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Cheep $cheep): void
    {
        $this->em->persist($cheep);
    }
    //end-ignore

    public function ofFollowersOfAuthor(Author $author): array
    {
        $entityRepository = $this->em->getRepository(Cheep::class);
        $queryBuilder = $entityRepository->createQueryBuilder('c');
        $expr = $queryBuilder->expr();
        $orExpression = $expr->orX();

        \Functional\each(
            $author->following(),
            static function (AuthorId $authorId, int $index)
                use ($orExpression, $expr, $queryBuilder): void {
                    $orExpression->add(
                        $expr->eq('c.authorId.id', '?' . ((string)($index + 1)))
                    );
                    $queryBuilder->setParameter(
                        $index + 1,
                        $authorId->id()->getBytes()
                    );
                }
        );

        $queryBuilder->where($orExpression);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function ofId(CheepId $cheepId): ?Cheep
    {
        return $this->em->find(Cheep::class, $cheepId->id());
    }
}
//end-snippet

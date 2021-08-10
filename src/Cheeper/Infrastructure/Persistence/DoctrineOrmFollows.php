<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\Cheeps;
use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\Follows;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

//snippet doctrine-orm-follows
final class DoctrineOrmFollows implements Follows
{
    //ignore
    public function __construct(
        private EntityManagerInterface $em
    ) { }
//
//    public function add(Cheep $cheep): void
//    {
//        $this->em->persist($cheep);
//    }
//    //end-ignore
//
//    public function ofFollowersOfAuthor(Author $author): array
//    {
//        $entityRepository = $this->em->getRepository(Cheep::class);
//        $queryBuilder = $entityRepository->createQueryBuilder('c');
//        $expr = $queryBuilder->expr();
//        $orExpression = $expr->orX();
//
//        \Functional\each(
//            $author->following(),
//            static function (AuthorId $authorId, int $index)
//                use ($orExpression, $expr, $queryBuilder): void {
//                    $orExpression->add(
//                        $expr->eq('c.authorId.id', '?' . ((string)($index + 1)))
//                    );
//                    $queryBuilder->setParameter(
//                        $index + 1,
//                        Uuid::fromString($authorId->id())->getBytes()
//                    );
//                }
//        );
//
//        $queryBuilder->where($orExpression);
//        $query = $queryBuilder->getQuery();
//
//        return $query->getResult();
//    }
//
//    public function ofId(CheepId $cheepId): ?Cheep
//    {
//        return $this->em->find(Cheep::class, Uuid::fromString($cheepId->id()));
//    }

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return 13;
    }

//    public function add(Cheep $cheep): void
//    {
//        $this->em->persist($cheep);
//    }

    public function save(Follow $follow): void
    {
        $this->em->persist($follow);
    }
}
//end-snippet

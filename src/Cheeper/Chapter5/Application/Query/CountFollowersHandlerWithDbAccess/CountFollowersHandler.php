<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithDbAccess;

use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Cheeper\DomainModel\Author\AuthorId;
use Doctrine\ORM\EntityManagerInterface;

//snippet count-followers-handler
/**
 * @psalm-import-type CountFollowersQueryResult from \Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector
 */
final class CountFollowersHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $connection = $this->entityManager->getConnection();

        $authorId = AuthorId::fromString($query->authorId());

        /** @psalm-var CountFollowersQueryResult $arrayResult */
        $arrayResult = $connection->fetchAssociative(
            "SELECT a.id as id, a.username as username, COUNT(*) as followers ".
            "FROM authors a, follow_relationships fr ".
            "WHERE a.id = fr.followed_id ".
            "AND a.id = :authorId ".
            "GROUP BY a.id, a.username",
            ['authorId' => $authorId->toString()]
        );

        return new CountFollowersResponse(
            authorId: $arrayResult['id'],
            authorUsername: $arrayResult['username'],
            numberOfFollowers: (int)$arrayResult['followers']
        );
    }
}
//end-snippet

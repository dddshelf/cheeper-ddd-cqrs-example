<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithDbAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Doctrine\ORM\EntityManagerInterface;

//snippet count-followers-handler
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

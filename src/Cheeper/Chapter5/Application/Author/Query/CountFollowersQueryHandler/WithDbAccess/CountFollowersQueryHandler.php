<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithDbAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Doctrine\ORM\EntityManagerInterface;

//snippet count-followers-handler
final class CountFollowersQueryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CountFollowersQuery $query): CountFollowersResponse
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

        if (false === $arrayResult) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        return new CountFollowersResponse(
            authorId: $arrayResult['id'],
            authorUsername: $arrayResult['username'],
            numberOfFollowers: (int)$arrayResult['followers']
        );
    }
}
//end-snippet

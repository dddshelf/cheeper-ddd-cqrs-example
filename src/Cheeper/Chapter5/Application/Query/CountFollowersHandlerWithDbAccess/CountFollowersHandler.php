<?php

declare(strict_types=1);

namespace Cheeper\Chapter5\Application\Query\CountFollowersHandlerWithDbAccess;

use Cheeper\Chapter5\Application\Query\CountFollowersResponse;
use Cheeper\Chapter5\Application\Query\CountFollowers;
use Cheeper\DomainModel\Author\AuthorId;
use Doctrine\DBAL\Portability\Connection as Dbal;

//snippet count-followers-handler
final class CountFollowersHandler
{
    public function __construct(
        private Dbal $dbalConnection
    ) {
    }

    public function __invoke(CountFollowers $query): CountFollowersResponse
    {
        $authorId = AuthorId::fromString($query->authorId());

        $stmt = $this->dbalConnection->prepare(
            "SELECT a.id as id, a.username as username, COUNT(*) as followers ".
            "FROM authors a, follow_relationships fr ".
            "WHERE a.id = fr.followed_id ".
            "AND a.id = :authorId ".
            "GROUP BY a.id, a.username"
        );

        $stmt->bindValue('authorId', $authorId->toString());

        $arrayResult = $stmt->fetchAllAssociative();

        return new CountFollowersResponse(
            authorId: $arrayResult['id'],
            authorUsername: $arrayResult['username'],
            numberOfFollowers: $arrayResult['followers']
        );
    }
}
//end-snippet

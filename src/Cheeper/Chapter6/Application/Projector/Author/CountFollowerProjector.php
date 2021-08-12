<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Projector\Author;

use Cheeper\Chapter6\Application\Projector\CountFollowers;
use Cheeper\DomainModel\Author\AuthorId;
use Doctrine\DBAL\Driver\Connection as Database;
use Predis\ClientInterface as Redis;

final class CountFollowerProjector
{
    public function __construct(
        private Redis $redis,
        private Database $database
    ) { }

    public function __invoke(CountFollowers $query): void
    {
        $authorId = AuthorId::fromString($query->authorId());

        $stmt = $this->database->prepare(
            "SELECT a.id as id, a.username as username, COUNT(*) as followers ".
            "FROM authors a, follow_relationships fr ".
            "WHERE a.id = fr.followed_id ".
            "AND a.id = :authorId ".
            "GROUP BY a.id, a.username"
        );

        $stmt->bindValue('authorId', $authorId->toString());

        $this->redis->set(
            'key',
            $stmt->fetchAllAssociative(),
        );
    }
}
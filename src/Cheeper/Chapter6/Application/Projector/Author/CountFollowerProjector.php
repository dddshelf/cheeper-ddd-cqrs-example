<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Projector\Author;

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
            "SELECT a.author_id_id_as_string as id, a.user_name_user_name as username, COUNT(*) as followers ".
            "FROM authors a, follows f ".
            "WHERE a.author_id_id_as_string = f.to_author_id_id_as_string ".
            "AND a.author_id_id_as_string = :authorId ".
            "GROUP BY id, username"
        );

        $stmt->bindValue('authorId', $authorId->toString());
        $stmt->execute();

        $result = $stmt->fetchAssociative();
        $result['followers'] = (int) $result['followers'];

        $this->redis->set(
            'author_followers_counter_projection:'.$authorId->toString(),
            json_encode($result)
        );
    }
}
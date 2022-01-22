<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Projector\Author;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Doctrine\ORM\EntityManagerInterface;

//snippet projector-count-followers
final class CountFollowerProjector
{
    public function __construct(
        private \Redis $redis,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CountFollowers $projection): void
    {
        $connection = $this->entityManager->getConnection();

        $authorId = AuthorId::fromString($projection->authorId());

        $result = $connection->fetchAssociative(
            "SELECT a.author_id as id, a.username as username, COUNT(*) as followers ".
            "FROM authors a, follows f ".
            "WHERE a.author_id = f.to_author_id ".
            "AND a.author_id = :authorId ".
            "GROUP BY id, username",
            ['authorId' => $authorId->toString()]
        );

        $result['followers'] = (int) $result['followers'];

        $this->redis->set(
            'author_followers_counter_projection:'.$authorId->toString(),
            json_encode($result)
        );
    }
}
//end-snippet

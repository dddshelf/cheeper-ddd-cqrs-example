<?php

declare(strict_types=1);

namespace Cheeper\Chapter6\Application\Author\Projection;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Doctrine\ORM\EntityManagerInterface;
use Redis;

//snippet projector-count-followers
final class CountFollowersProjectionHandler
{
    public function __construct(
        private Redis $redis,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CountFollowersProjection $projection): void
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

        if (false === $result) {
            throw AuthorDoesNotExist::withAuthorIdOf($authorId);
        }

        $this->redis->set(
            'author_followers_counter_projection:'.$authorId->toString(),
            json_encode([
                'id' => $result['id'],
                'username' => $result['username'],
                'followers' => (int) $result['followers'],
            ])
        );
    }
}
//end-snippet

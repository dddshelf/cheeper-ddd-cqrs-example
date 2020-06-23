<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\DomainModel\Author\UserName;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use function Functional\map;

//snippet doctrine-orm-authors
final class DoctrineOrmAuthors implements Authors
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function ofId(AuthorId $authorId): ?Author
    {
        $author = $this->em
            ->getRepository(Author::class)
            ->findOneBy([
                'authorId.id' => Uuid::fromString($authorId->id())
            ]);

        if (null === $author) {
            return null;
        }

        foreach ($this->followersOf($author) as $followedId) {
            $author->follow($followedId);
        }

        return $author;
    }

    public function ofUserName(UserName $userName): ?Author
    {
        $author = $this->em
            ->getRepository(Author::class)
            ->findOneBy(['userName.userName' => $userName->userName()]);

        if (null === $author) {
            return null;
        }

        foreach ($this->followersOf($author) as $followedId) {
            $author->follow($followedId);
        }

        return $author;
    }

    public function save(Author $author): void
    {
        $this->em->persist($author);

        $connection = $this->em->getConnection();

        $uuid = Uuid::fromString($author->userId()->id());

        /** @var AuthorId $userId */
        foreach ($author->following() as $userId) {
            try {
                $connection->insert(
                    'user_followers', [
                        'user_id' => $uuid->getBytes(),
                        'followed_id' => Uuid::fromString($userId->id())->getBytes(),
                    ]
                );
            } catch (UniqueConstraintViolationException $_) {

            }
        }
    }

    /** @return AuthorId[] */
    private function followersOf(Author $author): array
    {
        $connection = $this->em->getConnection();

        $stmt = $connection->prepare(
            "SELECT followed_id 
            FROM user_followers 
            WHERE user_id = :user_id"
        );

        $stmt->bindValue("user_id", Uuid::fromString($author->userId()->id())->getBytes());
        $stmt->execute();

        /** @var array{followed_id: string}[] $followers */
        $followers = $stmt->fetchAll();

        $uuids = map(
            $followers,
            fn(array $follower) => Uuid::fromBytes((string)$follower['followed_id'])
        );

        return map(
            $uuids,
            fn(UuidInterface $followedId) => AuthorId::fromUuid($followedId)
        );
    }
}
//end-snippet

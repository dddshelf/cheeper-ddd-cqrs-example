<?php

declare(strict_types=1);

namespace Cheeper\Tests\Infrastructure\Persistence;

use App\Helpers\ServiceLocatorForTests;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use Cheeper\Infrastructure\Persistence\DoctrineOrmAuthors;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineOrmAuthorsTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?DoctrineOrmAuthors $authorsRepositories;
    private ?UuidInterface $authorId;

    /** @test */
    public function followersAreSavedInASeparateTable(): void
    {
        $connection = $this->entityManager->getConnection();
        $count = (int)$connection->fetchColumn('SELECT COUNT(*) FROM user_followers');

        $this->assertSame(2, $count);
    }

    /** @test */
    public function onDuplicatesThereShouldNotThrowAnError(): void
    {
        $this->expectNotToPerformAssertions();

        $this->entityManager->clear();

        $authorWithFollowers = $this->authorsRepositories->ofId(AuthorId::fromUuid($this->authorId));

        try {
            $this->authorsRepositories->save($authorWithFollowers);
        } catch (\Exception $_) {
            $this->fail('An exception has been thrown when saving aggregate twice');
        }
    }

    /** @test */
    public function fetchesUserFollowers(): void
    {
        $this->entityManager->clear();

        $authorWithFollowers = $this->authorsRepositories->ofId(AuthorId::fromUuid($this->authorId));

        $this->assertNotNull($authorWithFollowers);
        $this->assertCount(2, $authorWithFollowers->following());

        \Functional\each(
            $authorWithFollowers->following(),
            function($authorId): void {
                $this->assertInstanceOf(AuthorId::class, $authorId);
            }
        );
    }

    /** @before */
    protected function prepareUsers()
    {
        $kernel = self::bootKernel();

        /** @var ServiceLocatorForTests $serviceLocator */
        $serviceLocator = $kernel
            ->getContainer()
            ->get(ServiceLocatorForTests::class);

        $doctrine = $serviceLocator->doctrine();
        $this->authorsRepositories = $serviceLocator->authors();

        $author1 = Author::signUp(
            AuthorId::fromUuid(Uuid::uuid4()),
            UserName::pick('test1'),
            'test1',
            'test1',
            'test1',
            new Website('http://test1.com'),
            new BirthDate((new \DateTimeImmutable())->format('Y-m-d'))
        );

        $this->authorsRepositories->save($author1);

        $author2 = Author::signUp(
            AuthorId::fromUuid(Uuid::uuid4()),
            UserName::pick('test2'),
            'test2',
            'test2',
            'test2',
            new Website('http://test2.com'),
            new BirthDate((new \DateTimeImmutable())->format('Y-m-d'))
        );

        $this->authorsRepositories->save($author2);

        $this->authorId = Uuid::uuid4();

        $authorWithFollowers = Author::signUp(
            AuthorId::fromUuid($this->authorId),
            UserName::pick('test'),
            'test',
            'test',
            'test',
            new Website('http://test.com'),
            new BirthDate((new \DateTimeImmutable())->format('Y-m-d'))
        );

        $authorWithFollowers->follow($author1->userId());
        $authorWithFollowers->follow($author2->userId());

        $this->authorsRepositories->save($authorWithFollowers);

        $this->entityManager = $doctrine->getManager();
        $this->entityManager->flush();
    }
}

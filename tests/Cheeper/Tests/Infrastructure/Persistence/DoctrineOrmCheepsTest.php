<?php

declare(strict_types=1);

namespace Cheeper\Tests\Infrastructure\Persistence;

use App\Helpers\ServiceLocatorForTests;
use App\Tests\Traits\RefreshDatabase;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DoctrineOrmCheepsTest extends WebTestCase
{
    use RefreshDatabase;

    /** @test */
    public function cheepsOfPeopleFollowing(): void
    {
        $client = static::createClient();

        /** @var ServiceLocatorForTests $serviceLocator */
        $serviceLocator = $client->getContainer()->get(ServiceLocatorForTests::class);

        $authors = $serviceLocator->authors();

        $author1Id = AuthorId::fromUuid(Uuid::uuid4());
        $author2Id = AuthorId::fromUuid(Uuid::uuid4());

        $author1 = Author::signUp(
            $author1Id,
            UserName::pick('test'),
            new EmailAddress('test@gmail.com'),
            'test',
            'test',
            'test',
            new Website('https://google.com/'),
            new BirthDate((new \DateTimeImmutable())->format('Y-m-d'))
        );

        $author2 = Author::signUp(
            $author2Id,
            UserName::pick('test2'),
            new EmailAddress('test2@gmail.com'),
            'test2',
            'test2',
            'test2',
            new Website('https://bing.com/'),
            new BirthDate((new \DateTimeImmutable())->format('Y-m-d'))
        );

        $author1->follow($author2->userId());

        $authors->save($author1);
        $authors->save($author2);

        $cheeps = $serviceLocator->cheeps();

        $cheeps->add(
            Cheep::compose(
                $author1Id,
                CheepId::fromUuid(Uuid::uuid4()),
                CheepMessage::write('test1')
            )
        );

        $cheeps->add(
            Cheep::compose(
                $author2Id,
                CheepId::fromUuid(Uuid::uuid4()),
                CheepMessage::write('test2')
            )
        );

        $doctrine = $serviceLocator->doctrine();
        $doctrine->getManager()->flush();

        $this->assertCount(1, $cheeps->ofFollowersOfAuthor($author1));
    }
}

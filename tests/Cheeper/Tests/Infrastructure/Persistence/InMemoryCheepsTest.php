<?php

declare(strict_types=1);

namespace Cheeper\Tests\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\BirthDate;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Author\Website;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\Infrastructure\Persistence\InMemoryCheeps;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class InMemoryCheepsTest extends TestCase
{
    /** @test */
    public function cheepsOfPeopleFollowing(): void
    {
        $author1Id = AuthorId::fromUuid(Uuid::uuid4());
        $author2Id = AuthorId::fromUuid(Uuid::uuid4());

        $author1 = Author::signUp(
            $author1Id,
            UserName::pick('test'),
            'test',
            'test',
            'test',
            new Website('https://google.com/'),
            new BirthDate(new \DateTimeImmutable())
        );

        $author2 = Author::signUp(
            $author2Id,
            UserName::pick('test2'),
            'test2',
            'test2',
            'test2',
            new Website('https://bing.com/'),
            new BirthDate(new \DateTimeImmutable())
        );

        $author1->follow($author2->userId());

        $cheeps = new InMemoryCheeps();

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

        $this->assertCount(1, $cheeps->ofFollowersOfAuthor($author1));
    }
}

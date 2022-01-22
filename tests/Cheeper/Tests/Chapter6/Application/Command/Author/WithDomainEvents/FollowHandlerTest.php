<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter6\Application\Command\Author\WithDomainEvents;

use Cheeper\Chapter6\Application\Command\Author\WithDomainEvents\FollowHandler;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\AllChapters\DomainModel\Follow\AuthorFollowed;
use Cheeper\AllChapters\DomainModel\Follow\Follow as FollowRelation;
use Cheeper\Chapter6\Application\Command\Author\Follow;
use Cheeper\AllChapters\DomainModel\Author\Author;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryFollows;
use Cheeper\Tests\Helper\SendsCommands;
use PHPUnit\Framework\TestCase;

final class FollowHandlerTest extends TestCase
{
    use SendsCommands;

    private const AUTHOR_ID_FROM = '400ea77d-0c8c-44f2-abe8-db05d0852966';
    private const AUTHOR_ID_TO = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

    private const USERNAME_KEYVAN = 'keyvanakbary';
    private const USERNAME_CARLOS = 'carlosbuenosvinos';
    private const EMAIL_KEYVAN = 'keyvan.akbary@gmail.com';
    private const EMAIL_CARLOS = 'carlos.buenosvinos@gmail.com';

    /** @test */
    public function givenTwoNonExistingAuthorsWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->runHandler(self::AUTHOR_ID_FROM, self::AUTHOR_ID_TO);
    }

    /** @test */
    public function givenOnlyFromAuthorIsNonExistingWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->authors->add(
            $this->buildAuthor(
                self::AUTHOR_ID_TO,
                self::USERNAME_CARLOS,
                self::EMAIL_CARLOS
            )
        );

        $this->runHandler(self::AUTHOR_ID_FROM, self::AUTHOR_ID_TO);
    }

    /** @test */
    public function givenOnlyToAuthorIsNonExistingWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->authors->add(
            $this->buildAuthor(
                self::AUTHOR_ID_FROM,
                self::USERNAME_CARLOS,
                self::EMAIL_CARLOS
            )
        );

        $this->runHandler(self::AUTHOR_ID_FROM, self::AUTHOR_ID_TO);
    }

    /** @test */
    public function givenTwoAuthorsFollowingOneToAnotherAlreadyWhenTryingToFollowAgainNothingShouldBeHappening(): void
    {
        $fromAuthorId = self::AUTHOR_ID_FROM;
        $toAuthorId = self::AUTHOR_ID_TO;
        $followId = '51d8ffff-123f-78e1-48fc-f8b513391a0e';

        $fromAuthor = Author::signUp(
            AuthorId::fromString($fromAuthorId),
            UserName::pick(self::USERNAME_CARLOS),
            EmailAddress::from(self::EMAIL_CARLOS)
        );

        $toAuthor = Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick(self::USERNAME_KEYVAN),
            EmailAddress::from(self::EMAIL_KEYVAN)
        );

        $this->authors->add($fromAuthor);
        $this->authors->add($toAuthor);
        $this->follows->add(
            FollowRelation::fromAuthorToAuthor(
                FollowId::fromString($followId),
                $fromAuthor->authorId(),
                $toAuthor->authorId(),
            )
        );

        $this->runHandler($fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $this->follows->collection
        );

        $events = $this->eventBus->events();
        $this->assertCount(0, $events);
    }

    /** @test */
    public function givenTwoAuthorsNotFollowingOneToAnotherWhenFollowingThenTheFollowShouldHappen(): void
    {
        $fromAuthorId = self::AUTHOR_ID_FROM;
        $toAuthorId = self::AUTHOR_ID_TO;

        $fromAuthor = Author::signUp(
            AuthorId::fromString($fromAuthorId),
            UserName::pick(self::USERNAME_CARLOS),
            EmailAddress::from(self::EMAIL_CARLOS)
        );

        $toAuthor = Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick(self::USERNAME_KEYVAN),
            EmailAddress::from(self::EMAIL_KEYVAN)
        );

        $this->authors->add($fromAuthor);
        $this->authors->add($toAuthor);

        $this->runHandler($fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $this->follows->collection
        );

        $events = $this->eventBus->events();
        $this->assertCount(1, $events);
        $this->assertSame(AuthorFollowed::class, $events[0]::class);
    }

    private function buildAuthor(string $toAuthorId, string $userName, string $email): Author
    {
        return Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick($userName),
            EmailAddress::from($email)
        );
    }

    private function runHandler(string $fromAuthorId, string $toAuthorId): void
    {
        $this->eventBus->reset();

        (new FollowHandler(
            $this->authors,
            $this->follows,
            $this->eventBus
        ))(
            Follow::fromAuthorIdToAuthorId(
                $fromAuthorId,
                $toAuthorId
            )
        );
    }
}

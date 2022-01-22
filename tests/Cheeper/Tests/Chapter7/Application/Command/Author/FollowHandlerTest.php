<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Command\Author;

use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\Chapter7\Application\Command\Author\FollowHandler;
use Cheeper\Chapter7\Application\Command\Author\Follow;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Follow\Follow as FollowRelation;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;
use Cheeper\Chapter7\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\Chapter7\Infrastructure\Persistence\InMemoryFollows;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use PHPUnit\Framework\TestCase;

final class FollowHandlerTest extends TestCase
{
    private const FOLLOW_ID = '337df284-d475-4cbd-89af-12d7451f73f1';
    private const AUTHOR_ID_FROM = '400ea77d-0c8c-44f2-abe8-db05d0852966';
    private const AUTHOR_ID_TO = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

    private const USERNAME_KEYVAN = 'keyvanakbary';
    private const USERNAME_CARLOS = 'carlosbuenosvinos';
    private const EMAIL_KEYVAN = 'keyvan.akbary@gmail.com';
    private const EMAIL_CARLOS = 'carlos.buenosvinos@gmail.com';

    protected function setUp(): void
    {
        $this->authors = new InMemoryAuthors();
        $this->follows = new InMemoryFollows();
        $this->eventBus = new InMemoryEventBus();
    }

    /** @test */
    public function givenTwoNonExistingAuthorsWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->runHandler(
            self::FOLLOW_ID,
            self::AUTHOR_ID_FROM,
            self::AUTHOR_ID_TO
        );
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

        $this->runHandler(
            self::FOLLOW_ID,
            self::AUTHOR_ID_FROM,
            self::AUTHOR_ID_TO
        );
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

        $this->runHandler(
            self::FOLLOW_ID,
            self::AUTHOR_ID_FROM,
            self::AUTHOR_ID_TO
        );
    }

    /** @test */
    public function givenTwoAuthorsFollowingOneToAnotherAlreadyWhenTryingToFollowAgainNothingShouldBeHappening(): void
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

        $this->follows->add(
            FollowRelation::fromAuthorToAuthor(
                FollowId::fromString(self::FOLLOW_ID),
                $fromAuthor->authorId(),
                $toAuthor->authorId(),
            )
        );

        $this->runHandler(
            self::FOLLOW_ID,
            self::AUTHOR_ID_FROM,
            self::AUTHOR_ID_TO
        );

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

        $command = $this->runHandler(
            self::FOLLOW_ID,
            $fromAuthorId,
            $toAuthorId
        );

        $this->assertCount(
            1,
            $this->follows->collection
        );

        $events = $this->eventBus->events();
        $this->assertCount(1, $events);

        $authorFollowedEvent = $events[0];
        $this->assertSame(AuthorFollowed::class, $authorFollowedEvent::class);
        $this->assertSame($command->messageId(), $authorFollowedEvent->messageReplyId());
        $this->assertSame($command->messageId(), $authorFollowedEvent->messageCorrelationId());
    }

    private function buildAuthor(string $toAuthorId, string $userName, string $email): Author
    {
        return Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick($userName),
            EmailAddress::from($email)
        );
    }

    private function runHandler(
        string $followId,
        string $fromAuthorId,
        string $toAuthorId
    ): Follow
    {
        $this->eventBus->reset();

        $command = Follow::fromArray([
            'follow_id' => $followId,
            'from_author_id' => $fromAuthorId,
            'to_author_id' => $toAuthorId
        ]);

        (new FollowHandler(
            $this->authors,
            $this->follows,
            $this->eventBus
        ))(
            $command
        );

        return $command;
    }
}

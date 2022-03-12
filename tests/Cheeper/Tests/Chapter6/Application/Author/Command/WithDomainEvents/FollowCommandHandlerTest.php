<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter6\Application\Author\Command\WithDomainEvents;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\AllChapters\DomainModel\Clock\DateCollectionClockStrategy;
use Cheeper\AllChapters\DomainModel\Follow\FollowDoesAlreadyExistException;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\Chapter4\DomainModel\Author\Author;
use Cheeper\Chapter4\DomainModel\Author\AuthorFollowed;
use Cheeper\Chapter4\DomainModel\Follow\Follow;
use Cheeper\Chapter4\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Chapter4\Infrastructure\DomainModel\Author\InMemoryAuthorRepository;
use Cheeper\Chapter4\Infrastructure\DomainModel\Follow\InMemoryFollowRepository;
use Cheeper\Chapter6\Application\Author\Command\FollowCommand;
use Cheeper\Chapter6\Application\Author\Command\WithDomainEvents\FollowCommandHandler;
use DateTimeImmutable;
use function Functional\first;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

final class FollowCommandHandlerTest extends TestCase
{
    private const AUTHOR_ID_FROM = '400ea77d-0c8c-44f2-abe8-db05d0852966';
    private const AUTHOR_ID_TO = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

    private const USERNAME_KEYVAN = 'keyvanakbary';
    private const USERNAME_CARLOS = 'carlosbuenosvinos';
    private const EMAIL_KEYVAN = 'keyvan.akbary@gmail.com';
    private const EMAIL_CARLOS = 'carlos.buenosvinos@gmail.com';

    private InMemoryAuthorRepository $authorRepository;
    private InMemoryEventBus $eventBus;
    private InMemoryFollowRepository $followRepository;
    private DateTimeImmutable $date;

    protected function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();
        $this->eventBus = new InMemoryEventBus();
        $this->date = new DateTimeImmutable(
            'now',
            new \DateTimeZone(
                'UTC'
            )
        );

        Clock::instance()->changeStrategy(
            new DateCollectionClockStrategy([$this->date])
        );
    }

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

        $this->authorRepository->add(
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

        $this->authorRepository->add(
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
        $this->expectException(FollowDoesAlreadyExistException::class);

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

        $this->authorRepository->add($fromAuthor);
        $this->authorRepository->add($toAuthor);
        $this->followRepository->add(
            Follow::fromAuthorToAuthor(
                FollowId::fromString($followId),
                $fromAuthor->authorId(),
                $toAuthor->authorId(),
            )
        );

        $this->runHandler($fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $this->followRepository->collection
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

        $this->authorRepository->add($fromAuthor);
        $this->authorRepository->add($toAuthor);

        $this->runHandler($fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $this->followRepository->collection
        );

        $events = $this->eventBus->events();
        /** @var AuthorFollowed $authorFollowed */
        $authorFollowed = first($events);
        $this->assertCount(1, $events);
        $this->assertSame(AuthorFollowed::class, $authorFollowed::class);
        $this->assertSame($fromAuthorId, $authorFollowed->fromAuthorId());
        $this->assertSame($toAuthorId, $authorFollowed->toAuthorId());
        $this->assertSame($this->date, $authorFollowed->occurredOn());
        $this->assertTrue(UuidV4::isValid($authorFollowed->followId()));
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

        (new FollowCommandHandler(
            $this->authorRepository,
            $this->followRepository,
            $this->eventBus
        ))(
            FollowCommand::fromAuthorIdToAuthorId(
                $fromAuthorId,
                $toAuthorId
            )
        );
    }
}

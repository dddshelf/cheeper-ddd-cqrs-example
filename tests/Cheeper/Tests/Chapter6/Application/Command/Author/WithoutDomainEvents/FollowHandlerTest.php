<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter6\Application\Command\WithoutDomainEvents\Author;

use Cheeper\Chapter6\Application\Command\Author\WithoutDomainEvents\FollowHandler;
use Cheeper\DomainModel\Follow\Follow as FollowRelation;
use Cheeper\Chapter6\Application\Command\Author\Follow;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\FollowId;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\Infrastructure\Persistence\InMemoryFollows;
use PHPUnit\Framework\TestCase;

final class FollowHandlerTest extends TestCase
{
    private const AUTHOR_ID_FROM = '400ea77d-0c8c-44f2-abe8-db05d0852966';
    private const AUTHOR_ID_TO = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

    const USERNAME_KEYVAN = 'keyvanakbary';
    const USERNAME_CARLOS = 'carlosbuenosvinos';
    const EMAIL_KEYVAN = 'keyvan.akbary@gmail.com';
    const EMAIL_CARLOS = 'carlos.buenosvinos@gmail.com';

    private InMemoryAuthors $authorsRepository;
    private InMemoryFollows $followsRepository;

    protected function setUp(): void
    {
        $this->authorsRepository = new InMemoryAuthors();
        $this->followsRepository = new InMemoryFollows();
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

        $this->authorsRepository->save($this->buildAuthor(
            self::AUTHOR_ID_TO,
            self::USERNAME_CARLOS,
            self::EMAIL_CARLOS
        ));

        $this->runHandler(self::AUTHOR_ID_FROM, self::AUTHOR_ID_TO);
    }

    /** @test */
    public function givenOnlyToAuthorIsNonExistingWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->authorsRepository->save(
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

        $this->authorsRepository->save($fromAuthor);
        $this->authorsRepository->save($toAuthor);
        $this->followsRepository->save(
            new FollowRelation(
                FollowId::fromString($followId),
                $fromAuthor->authorId(),
                $toAuthor->authorId(),
            )
        );

        $this->runHandler($fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $this->followsRepository->collection
        );
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

        $this->authorsRepository->save($fromAuthor);
        $this->authorsRepository->save($toAuthor);

        $this->runHandler($fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $this->followsRepository->collection
        );
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
        (new FollowHandler(
            $this->authorsRepository,
            $this->followsRepository
        ))(
            Follow::fromAuthorIdToAuthorId(
                $fromAuthorId,
                $toAuthorId
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace Cheeper\Tests\Cheeper6\Application\Author;

use Cheeper\DomainModel\Follow\Follow as FollowRelation;
use Cheeper\Chapter6\Application\Command\Author\Follow;
use Cheeper\Chapter6\Application\Command\Author\FollowHandler;
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
    /** @test */
    public function givenTwoNonExistingAuthorsWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $fromAuthorId = '400ea77d-0c8c-44f2-abe8-db05d0852966';
        $toAuthorId = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

        $this->expectException(AuthorDoesNotExist::class);

        $authorsRepository = new InMemoryAuthors();
        $followsRepository = new InMemoryFollows();
        $this->runHandler($authorsRepository, $followsRepository, $fromAuthorId, $toAuthorId);
    }

    /** @test */
    public function givenOnlyFromAuthorIsNonExistingWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $fromAuthorId = '400ea77d-0c8c-44f2-abe8-db05d0852966';
        $toAuthorId = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

        $toAuthor = Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick('carlosbuenosvinos'),
            EmailAddress::from('carlos.buenosvinos@gmail.com')
        );

        $authorsRepository = new InMemoryAuthors();
        $followsRepository = new InMemoryFollows();
        $authorsRepository->save($toAuthor);

        $this->runHandler($authorsRepository, $followsRepository, $fromAuthorId, $toAuthorId);
    }

    /** @test */
    public function givenOnlyToAuthorIsNonExistingWhenFollowingOneToAnotherOneNonExistingAuthorExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $fromAuthorId = '400ea77d-0c8c-44f2-abe8-db05d0852966';
        $toAuthorId = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

        $fromAuthor = Author::signUp(
            AuthorId::fromString($fromAuthorId),
            UserName::pick('carlosbuenosvinos'),
            EmailAddress::from('carlos.buenosvinos@gmail.com')
        );

        $authorsRepository = new InMemoryAuthors();
        $followsRepository = new InMemoryFollows();
        $authorsRepository->save($fromAuthor);

        $this->runHandler($authorsRepository, $followsRepository, $fromAuthorId, $toAuthorId);
    }

    /** @test */
    public function givenTwoAuthorsFollowingOneToAnotherAlreadyWhenTryingToFollowAgainNothingShouldBeHappening(): void
    {
        $fromAuthorId = '400ea77d-0c8c-44f2-abe8-db05d0852966';
        $toAuthorId = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';
        $followId = '51d8ffff-123f-78e1-48fc-f8b513391a0e';

        $fromAuthor = Author::signUp(
            AuthorId::fromString($fromAuthorId),
            UserName::pick('carlosbuenosvinos'),
            EmailAddress::from('carlos.buenosvinos@gmail.com')
        );

        $toAuthor = Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick('carlosbuenosvinos'),
            EmailAddress::from('carlos.buenosvinos@gmail.com')
        );

        $authorsRepository = new InMemoryAuthors();
        $followsRepository = new InMemoryFollows();

        $authorsRepository->save($fromAuthor);
        $authorsRepository->save($toAuthor);
        $followsRepository->save(
            new FollowRelation(
                FollowId::fromString($followId),
                $fromAuthor->authorId(),
                $toAuthor->authorId(),
            )
        );

        $this->runHandler($authorsRepository, $followsRepository, $fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $followsRepository->collection
        );
    }

    /** @test */
    public function givenTwoAuthorsNotFollowingOneToAnotherWhenFollowingThenTheFollowShouldHappen(): void
    {
        $fromAuthorId = '400ea77d-0c8c-44f2-abe8-db05d0852966';
        $toAuthorId = '52d8f0b5-544f-46e0-84dc-f8b513391a0e';

        $fromAuthor = Author::signUp(
            AuthorId::fromString($fromAuthorId),
            UserName::pick('carlosbuenosvinos'),
            EmailAddress::from('carlos.buenosvinos@gmail.com')
        );

        $toAuthor = Author::signUp(
            AuthorId::fromString($toAuthorId),
            UserName::pick('carlosbuenosvinos'),
            EmailAddress::from('carlos.buenosvinos@gmail.com')
        );

        $authorsRepository = new InMemoryAuthors();
        $followsRepository = new InMemoryFollows();

        $authorsRepository->save($fromAuthor);
        $authorsRepository->save($toAuthor);

        $this->runHandler($authorsRepository, $followsRepository, $fromAuthorId, $toAuthorId);

        $this->assertCount(
            1,
            $followsRepository->collection
        );
    }

    private function runHandler(InMemoryAuthors $authorsRepository, InMemoryFollows $followsRepository, string $fromAuthorId, string $toAuthorId): void
    {
        (new FollowHandler(
            $authorsRepository,
            $followsRepository
        ))(
            Follow::fromAuthorIdToAuthorId(
                $fromAuthorId,
                $toAuthorId
            )
        );
    }
}

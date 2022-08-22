<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\FollowApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryFollowRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use PHPUnit\Framework\TestCase;

final class FollowApplicationServiceTest extends TestCase
{
    private AuthorRepository $authorRepository;
    private FollowApplicationService $followApplicationService;
    private FollowRepository $followRepository;

    protected function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();

        $this->followApplicationService = new FollowApplicationService($this->followRepository, $this->authorRepository);
    }

    /** @test */
    public function givenANonExistingAuthorWhenFollowUseCaseIsExecutedThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->followApplicationService->followTo(
            AuthorTestDataBuilder::anAuthorIdentity()->toString(),
            AuthorTestDataBuilder::anAuthorIdentity()->toString(),
        );
    }

    /** @test */
    public function givenANonExistingAuthorToFollowWhenFollowUseCaseIsExecutedThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $author = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);

        $this->followApplicationService->followTo(
            $author->authorId()->toString(),
            AuthorTestDataBuilder::anAuthorIdentity()->toString(),
        );
    }

    /** @test */
    public function givenTwoExistingAuthorsWhenFollowUseCaseIsExecutedThenAnExceptionShouldBeThrown(): void
    {
        $fromAuthor = AuthorTestDataBuilder::anAuthor()->build();
        $toAuthor = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($fromAuthor);
        $this->authorRepository->add($toAuthor);

        $this->followApplicationService->followTo(
            $fromAuthor->authorId()->toString(),
            $toAuthor->authorId()->toString()
        );

        $follows = $this->followRepository->toAuthorId($toAuthor->authorId());

        $this->assertCount(1, $follows);
    }

    /** @test */
    public function givenFollowersCountForANonExistingAuthorWhenCountIsRequestedThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->followApplicationService->countFollowersOf(
            AuthorTestDataBuilder::anAuthorIdentity()->toString()
        );
    }

    /** @test */
    public function givenFollowersCountWhenCountIsRequestedThenItShouldReturnTheTotalNumberOfFollowers(): void
    {
        $author = AuthorTestDataBuilder::anAuthor()->build();

        $follower1 = AuthorTestDataBuilder::anAuthor()->build();
        $follower2 = AuthorTestDataBuilder::anAuthor()->build();
        $follower3 = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);
        $this->authorRepository->add($follower1);
        $this->authorRepository->add($follower2);
        $this->authorRepository->add($follower3);

        $this->followApplicationService->followTo($follower1->authorId()->toString(), $author->authorId()->toString());
        $this->followApplicationService->followTo($follower2->authorId()->toString(), $author->authorId()->toString());
        $this->followApplicationService->followTo($follower3->authorId()->toString(), $author->authorId()->toString());

        $totalNumberOfFollowers = $this->followApplicationService->countFollowersOf($author->authorId()->toString());

        $this->assertSame(3, $totalNumberOfFollowers);
    }
}
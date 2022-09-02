<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CountFollowersQuery;
use Cheeper\Application\CountFollowersQueryHandler;
use Cheeper\Application\FollowApplicationService;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryFollowRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use PHPUnit\Framework\TestCase;

final class CountFollowersQueryHandlerTest extends TestCase
{
    private AuthorRepository $authorRepository;
    private FollowRepository $followRepository;
    private FollowApplicationService $followApplicationService;
    private CountFollowersQueryHandler $countFollowersQueryHandler;

    protected function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();

        $this->followApplicationService = new FollowApplicationService($this->followRepository, $this->authorRepository);
        $this->countFollowersQueryHandler = new CountFollowersQueryHandler($this->authorRepository, $this->followRepository);
    }

    /** @test */
    public function givenFollowersCountForANonExistingAuthorWhenCountIsRequestedThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $authorId = AuthorTestDataBuilder::anAuthorIdentity()->id;

        ($this->countFollowersQueryHandler)(
            new CountFollowersQuery($authorId)
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

        $authorId = $author->authorId()->id;

        $this->followApplicationService->followTo($follower1->authorId()->id, $authorId);
        $this->followApplicationService->followTo($follower2->authorId()->id, $authorId);
        $this->followApplicationService->followTo($follower3->authorId()->id, $authorId);

        $totalNumberOfFollowers = ($this->countFollowersQueryHandler)(
            new CountFollowersQuery($authorId)
        );

        $this->assertSame(3, $totalNumberOfFollowers);
    }
}
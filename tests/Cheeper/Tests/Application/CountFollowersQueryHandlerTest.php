<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CountFollowers\CountFollowersQuery;
use Cheeper\Application\CountFollowers\CountFollowersQueryHandler;
use Cheeper\Application\Follow\FollowCommand;
use Cheeper\Application\Follow\FollowCommandHandler;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;
use Cheeper\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryFollowRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use PHPUnit\Framework\TestCase;

final class CountFollowersQueryHandlerTest extends TestCase
{
    private AuthorRepository $authorRepository;
    private FollowRepository $followRepository;
    private CountFollowersQueryHandler $countFollowersQueryHandler;

    protected function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();

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

        $followCommandHandler = new FollowCommandHandler($this->authorRepository, $this->followRepository, new InMemoryEventBus());
        ($followCommandHandler)(new FollowCommand($follower1->authorId()->id, $authorId));
        ($followCommandHandler)(new FollowCommand($follower2->authorId()->id, $authorId));
        ($followCommandHandler)(new FollowCommand($follower3->authorId()->id, $authorId));

        $totalNumberOfFollowers = ($this->countFollowersQueryHandler)(
            new CountFollowersQuery($authorId)
        );

        $this->assertSame(3, $totalNumberOfFollowers);
    }
}
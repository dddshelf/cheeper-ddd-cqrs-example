<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\AuthorWasFollowed\AuthorWasFollowedEventHandler;
use Cheeper\Application\CountFollowers\CountFollowersQuery;
use Cheeper\Application\CountFollowers\CountFollowersQueryHandler;
use Cheeper\Application\Follow\FollowCommand;
use Cheeper\Application\Follow\FollowCommandHandler;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\AuthorWasFollowed;
use Cheeper\DomainModel\Follow\FollowRepository;
use Cheeper\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryFollowRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psl\Iter;
use Psl\Type;
use Redis;

final class CountFollowersQueryHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<Redis>  */
    private ObjectProphecy $redis;
    private AuthorRepository $authorRepository;
    private FollowRepository $followRepository;
    private CountFollowersQueryHandler $countFollowersQueryHandler;
    private AuthorWasFollowedEventHandler $authorWasFollowedEventHandler;
    private int $followers = 0;

    protected function setUp(): void
    {
        $this->redis = $this->prophesize(\Redis::class);
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();

        $redis = $this->redis->reveal();
        $this->countFollowersQueryHandler = new CountFollowersQueryHandler($redis);
        $this->authorWasFollowedEventHandler = new AuthorWasFollowedEventHandler($redis);
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

        $eventBus = new InMemoryEventBus();

        $followCommandHandler = new FollowCommandHandler($this->authorRepository, $this->followRepository, $eventBus);
        ($followCommandHandler)(new FollowCommand($follower1->authorId()->id, $authorId));
        ($followCommandHandler)(new FollowCommand($follower2->authorId()->id, $authorId));
        ($followCommandHandler)(new FollowCommand($follower3->authorId()->id, $authorId));

        $followersCounter = new class() {
            private int $count = 0;

            public function increment(): void
            {
                ++$this->count;
            }

            public function getCount(): int
            {
                return $this->count;
            }
        };

        $this->redis->incr(Argument::type('string'))->will(function () use($followersCounter): int {
            $followersCounter->increment();
            return $followersCounter->getCount();
        });

        $this->redis->get(Argument::type('string'))->will(static fn() => $followersCounter->getCount());
        $this->redis->exists(Argument::type('string'))->willReturn(true);

        $events = Type\vec(
            Type\instance_of(AuthorWasFollowed::class)
        )->coerce($eventBus->getEvents());

        Iter\apply($events, ($this->authorWasFollowedEventHandler)(...));

        $queryResponse = ($this->countFollowersQueryHandler)(
            new CountFollowersQuery($authorId)
        );

        $this->assertSame(3, $queryResponse->totalNumberOfFollowers);
    }
}
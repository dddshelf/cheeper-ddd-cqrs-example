<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\AddCheepToFollowersTimeline\AddCheepToFollowersTimelineProjection;
use Cheeper\Application\AddCheepToFollowersTimeline\AddCheepToFollowerTimelineProjectionHandler;
use Cheeper\Application\AuthorApplicationService;
use Cheeper\Application\CheepWasPosted\CheepWasPostedEventHandler;
use Cheeper\Application\Follow\FollowCommand;
use Cheeper\Application\Follow\FollowCommandHandler;
use Cheeper\Application\PostCheep\PostCheepCommand;
use Cheeper\Application\PostCheep\PostCheepCommandHandler;
use Cheeper\Application\Timeline\TimelineQuery;
use Cheeper\Application\Timeline\TimelineQueryHandler;
use Cheeper\Application\Timeline\TimelineQueryResponse;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\AuthorWasFollowed;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Cheeper\DomainModel\Cheep\CheepWasPosted;
use Cheeper\DomainModel\Follow\FollowRepository;
use Cheeper\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Infrastructure\Application\InMemoryProjectionBus;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryCheepRepository;
use Cheeper\Infrastructure\Persistence\InMemoryFollowRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use Cheeper\Tests\DomainModel\Cheep\CheepTestDataBuilder;
use Faker\Factory as FakerFactory;
use Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psl\{Dict, Iter, Vec, Type};
use Ramsey\Uuid\Uuid;

final class TimelineQueryHandlerTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy<\Redis> */
    private ObjectProphecy $redis;
    private CheepRepository $cheepRepository;
    private AuthorRepository $authorRepository;
    private TimelineQueryHandler $timelineQueryHandler;
    private CheepWasPostedEventHandler $cheepWasPostedEventHandler;
    private InMemoryProjectionBus $projectionBus;
    private AddCheepToFollowerTimelineProjectionHandler $addCheepToFollowerTimelineProjectionHandler;
    private InMemoryFollowRepository $followRepository;
    private AuthorApplicationService $authorApplicationService;
    private InMemoryEventBus $eventBus;
    private FollowCommandHandler $followCommandHandler;
    private PostCheepCommandHandler $postCheepCommandHandler;

    public function setUp(): void
    {
        $this->redis = $this->prophesize(\Redis::class);
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->projectionBus = new InMemoryProjectionBus();
        $this->followRepository = new InMemoryFollowRepository();
        $this->eventBus = new InMemoryEventBus();

        $redis = $this->redis->reveal();
        $this->timelineQueryHandler = new TimelineQueryHandler($redis);
        $this->cheepWasPostedEventHandler = new CheepWasPostedEventHandler($this->projectionBus, $this->followRepository);
        $this->addCheepToFollowerTimelineProjectionHandler = new AddCheepToFollowerTimelineProjectionHandler($redis);
        $this->authorApplicationService = new AuthorApplicationService($this->authorRepository);
        $this->followCommandHandler = new FollowCommandHandler($this->authorRepository, $this->followRepository, $this->eventBus);
        $this->postCheepCommandHandler = new PostCheepCommandHandler($this->authorRepository, $this->cheepRepository, $this->eventBus);
    }

    /** @test */
    public function givenATimelineRequestWhenTheAuthorDoesNotExistThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->timelineFrom(AuthorTestDataBuilder::anAuthorIdentity()->id, 0, 1);
    }

    /** @test */
    public function givenATimelineRequestWhenExecutionGoesWellThenAListOfCheepsShouldBeReturned(): void
    {
        $faker = FakerFactory::create();

        /** @psalm-suppress ArgumentTypeCoercion */
        $author1 = $this->authorApplicationService->signUp(
            Uuid::uuid6()->toString(),
            $faker->username(),
            $faker->email(),
            $faker->name(),
            $faker->text(),
            $faker->country(),
            $faker->url(),
            $faker->date()
        );

        /** @psalm-suppress ArgumentTypeCoercion */
        $author2 = $this->authorApplicationService->signUp(
            Uuid::uuid6()->toString(),
            $faker->userName(),
            $faker->email(),
            $faker->name(),
            $faker->text(),
            $faker->country(),
            $faker->url(),
            $faker->date()
        );

        ($this->followCommandHandler)(
            new FollowCommand(
                $author2->authorId()->id,
                $author1->authorId()->id
            )
        );

        $this->eventBus->flush();

        /** @psalm-suppress ArgumentTypeCoercion */
        ($this->postCheepCommandHandler)(
            new PostCheepCommand(
                Uuid::uuid6()->toString(),
                $author1->userName()->userName,
                $faker->text(260)
            )
        );

        /** @psalm-suppress ArgumentTypeCoercion */
        ($this->postCheepCommandHandler)(
            new PostCheepCommand(
                Uuid::uuid6()->toString(),
                $author1->userName()->userName,
                $faker->text(260)
            )
        );

        /** @psalm-suppress ArgumentTypeCoercion */
        ($this->postCheepCommandHandler)(
            new PostCheepCommand(
                Uuid::uuid6()->toString(),
                $author1->userName()->userName,
                $faker->text(260)
            )
        );

        $domainEvents = Type\vec(Type\instance_of(CheepWasPosted::class))
            ->coerce($this->eventBus->getEvents());

        Iter\apply($domainEvents, ($this->cheepWasPostedEventHandler)(...));

        $projections = Type\vec(Type\instance_of(AddCheepToFollowersTimelineProjection::class))
            ->coerce($this->projectionBus->getProjections());

        $timelinesDatabase = new class() {
            /** @psalm-var array<string, string[]> */
            private array $timelines = [];

            public function addCheep(string $key, string $serializedCheep): void
            {
                $this->timelines[$key][] = $serializedCheep;
            }

            public function getTimeline(string $timelineKey): array
            {
                return $this->timelines[$timelineKey];
            }

            public function getTimelines(): array
            {
                return $this->timelines;
            }
        };

        $this->redis
            ->lPush(Argument::type('string'), Argument::type('string'))
            ->will(static function(array $args) use ($timelinesDatabase) {
                [$key, $serializedCheep] = Type\vec(Type\non_empty_string())->coerce($args);
                $timelinesDatabase->addCheep($key, $serializedCheep);
            })
        ;

        $this->redis
            ->lRange(Argument::type('string'), Argument::type('int'), Argument::type('int'))
            ->will(static function(array $args) use ($timelinesDatabase) {
                [$key, $offset, $size] = Type\shape([0 => Type\non_empty_string(), 1 => Type\union(Type\positive_int(), Type\literal_scalar(0)), 2 => Type\positive_int()])->coerce($args);
                return Dict\slice($timelinesDatabase->getTimeline($key), $offset, $size);
            });

        $this->redis
            ->exists(Argument::type('string'))
            ->will(static function(array $args) use ($timelinesDatabase) {
                [$key] = Type\shape([0 => Type\non_empty_string()])->coerce($args);
                return array_key_exists($key, $timelinesDatabase->getTimelines());
            })
        ;

        Iter\apply($projections, ($this->addCheepToFollowerTimelineProjectionHandler)(...));

        $this->assertCount(
            3,
            $this->timelineFrom($author2->authorId()->id, 0, 10)->timeline
        );
    }

    /**
     * @param non-empty-string $authorId
     * @param positive-int|0 $offset
     * @param positive-int $size
     * @return TimelineQueryResponse
     */
    private function timelineFrom(string $authorId, int $offset, int $size): TimelineQueryResponse
    {
        return ($this->timelineQueryHandler)(
            new TimelineQuery($authorId, $offset, $size)
        );
    }
}
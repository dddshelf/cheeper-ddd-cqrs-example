<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CheepApplicationService;
use Cheeper\Application\Timeline\TimelineQuery;
use Cheeper\Application\Timeline\TimelineQueryHandler;
use Cheeper\Application\Timeline\TimelineQueryResponse;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryCheepRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use Cheeper\Tests\DomainModel\Cheep\CheepTestDataBuilder;
use PHPUnit\Framework\TestCase;
use Psl\Iter;

final class TimelineQueryHandlerTest extends TestCase
{
    private CheepRepository $cheepRepository;
    private AuthorRepository $authorRepository;
    private CheepApplicationService $cheepService;
    private TimelineQueryHandler $timelineQueryHandler;

    public function setUp(): void
    {
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->cheepService = new CheepApplicationService($this->authorRepository, $this->cheepRepository);
        $this->timelineQueryHandler = new TimelineQueryHandler($this->authorRepository, $this->cheepRepository);
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
        $author = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);

        $cheeps = [
            CheepTestDataBuilder::aCheep()->withAMessage("test1")->build(),
            CheepTestDataBuilder::aCheep()->withAMessage("test2")->build(),
            CheepTestDataBuilder::aCheep()->withAMessage("test3")->build(),
        ];

        Iter\apply($cheeps, fn(Cheep $c) => $this->cheepRepository->add($c));

        $this->assertCount(
            count($cheeps),
            $this->timelineFrom($author->authorId()->id, 0, 10)->timeline
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
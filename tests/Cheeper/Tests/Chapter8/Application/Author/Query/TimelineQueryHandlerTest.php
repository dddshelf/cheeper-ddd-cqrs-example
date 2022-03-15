<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter8\Application\Author\Query;

use App\Repository\PopularCheepRepository;
use Cheeper\Chapter8\Application\Author\Query\TimelineQuery;
use Cheeper\Chapter8\Application\Author\Query\TimelineQueryHandler;
use Cheeper\Chapter8\Infrastructure\DomainModel\Follow\InMemoryFollowRepository;
use PHPUnit\Framework\TestCase;
use Redis;
use Symfony\Component\Uid\Uuid;

final class TimelineQueryHandlerTest extends TestCase
{
    /**
     * @test
     * @Given An Author
     * @When Retrieving his or her timeline with no cheeps posted
     * @Then The Timeline should be empty
     */
    public function noCheepsHaveBeenPosted(): void
    {
        $redisStub = $this->createStub(Redis::class);
        $redisStub->method('lRange')->willReturn([]);

        $timelineQueryHandler = new TimelineQueryHandler(
            $redisStub,
            $this->createStub(PopularCheepRepository::class),
            new InMemoryFollowRepository()
        );

        $timelineQuery = TimelineQuery::fromArray([
            'author_id' => Uuid::v4()->toRfc4122(),
            'offset' => 0,
            'size' => 100
        ]);

        $timelineQueryResponse = $timelineQueryHandler->__invoke($timelineQuery);
        $this->assertEmpty($timelineQueryResponse->cheeps);
    }
}
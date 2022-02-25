<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Author\Query;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Cheeper\Chapter7\Application\Author\Query\CountFollowersQuery;
use Cheeper\Chapter7\Application\Author\Query\CountFollowersQueryHandler;
use Cheeper\Chapter7\Application\Author\Query\TimelineQuery;
use Cheeper\Chapter7\Application\Author\Query\TimelineQueryHandler;
use PHPUnit\Framework\TestCase;
use Redis;

//snippet timeline-query-handler-test
final class TimelineQueryHandlerTest extends TestCase
{
    /**
     * @test
     * @Given Non Existing Author
     * @When Fetching Timeline
     * @Then Empty Cheeps Collection should be returned
     */
    public function nonExistingAuthor(): void
    {
        $nonExistingAuthorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $queryHandler = new TimelineQueryHandler(
            $this->buildRedisMockReturning([])
        );

        $timelineResponse = $queryHandler->__invoke(
            TimelineQuery::fromArray([
                'author_id' => $nonExistingAuthorId,
                'offset' => 0,
                'size' => 20,
            ])
        );

        $this->assertEmpty($timelineResponse->cheeps);
    }

    private function buildRedisMockReturning($fakeReturn): Redis
    {
        $mock = $this->createStub(Redis::class);
        $mock->method('lRange')->willReturn(
            $fakeReturn
        );

        return $mock;
    }
}
//end-snippet

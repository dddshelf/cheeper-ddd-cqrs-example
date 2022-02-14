<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRedisAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRedisAccess\CountFollowersQueryHandler;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use PHPUnit\Framework\TestCase;

final class CountFollowersQueryHandlerTest extends TestCase
{
    /** @test */
    public function givenNoExistingAuthorWhenCountingFollowersThenEmptyResultIsReturned(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "3409a21d-83b3-471e-a4f1-cf6748af65d2" does not exist');

        $nonExistingAuthorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $queryHandler = new CountFollowersQueryHandler(
            $this->buildRedisMockReturning(false)
        );

        $queryHandler->__invoke(
            CountFollowersQuery::ofAuthor($nonExistingAuthorId)
        );
    }

    /** @test */
    public function givenExistingAuthorWhenCountingFollowersThenProperResultIsReturned(): void
    {
        $authorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $authorUsername = 'buenosvinos';
        $authorFollowers = 0;
        $queryHandler = new CountFollowersQueryHandler(
            $this->buildRedisMockReturning(
                json_encode(['id' => $authorId, 'username' => $authorUsername, 'followers' => $authorFollowers])
            )
        );

        $expectedReponse = new CountFollowersResponse(
            authorId: $authorId,
            authorUsername: $authorUsername,
            numberOfFollowers: $authorFollowers
        );

        $actualResponse = $queryHandler->__invoke(
            CountFollowersQuery::ofAuthor($authorId)
        );

        $this->assertSame($expectedReponse->authorId(), $actualResponse->authorId());
        $this->assertSame($expectedReponse->authorUsername(), $actualResponse->authorUsername());
        $this->assertSame($expectedReponse->numberOfFollowers(), $actualResponse->numberOfFollowers());

        // In PHPUnit, there is also the chance to
        // compare the whole content of the object
        // $this->assertEquals($expectedReponse, $actualResponse);
    }

    private function buildRedisMockReturning($fakeReturn) {
        $mock = $this->createStub(\Redis::class);
        $mock->method('get')->willReturn(
            $fakeReturn
        );

        return $mock;
    }
}

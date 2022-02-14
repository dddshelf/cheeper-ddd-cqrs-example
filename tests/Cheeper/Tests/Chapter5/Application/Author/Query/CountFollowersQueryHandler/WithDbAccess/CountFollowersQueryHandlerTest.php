<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithDbAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithDbAccess\CountFollowersQueryHandler;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use Doctrine\ORM\EntityManagerInterface;
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
            $this->buildEntityManagerMockReturning(false)
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
            $this->buildEntityManagerMockReturning(
                ['id' => $authorId, 'username' => $authorUsername, 'followers' => $authorFollowers]
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

        $this->assertEquals($expectedReponse, $actualResponse);
    }

    private function buildEntityManagerMockReturning($fakeReturn) {
        $mock = $this->createStub(EntityManagerInterface::class);

        $connectionMock = new class($fakeReturn) {
            public function __construct(private $toReturn) {
            }

            public function fetchAssociative($query, $params): mixed {
                return $this->toReturn;
            }
        };

        $mock->method('getConnection')->willReturn(
            $connectionMock
        );

        return $mock;
    }
}

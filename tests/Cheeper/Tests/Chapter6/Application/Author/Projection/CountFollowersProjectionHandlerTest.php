<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter6\Application\Author\Projection;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter6\Application\Author\Projection\CountFollowersProjectionHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class CountFollowersProjectionHandlerTest extends TestCase
{
    /**
     * @test
     * @Given Non Existing Author
     * @When Counting Followers
     * @Then Non Existing Author Exception Should Be Thrown
     */
    public function nonExistingAuthor(): void
    {
        $authorId = '1c22ed61-c305-44dd-a558-f261f434f583';

        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "1c22ed61-c305-44dd-a558-f261f434f583" does not exist');

        $redisMock = $this->createMock(\Redis::class);
        $dbMock = $this->buildEntityManagerMockReturning(false);

        $handler = new CountFollowersProjectionHandler(
            $redisMock,
            $dbMock
        );

        $handler(
            CountFollowersProjection::ofAuthor($authorId)
        );
    }

    /**
     * @test
     * @Given An Existing Author With 0 Followers
     * @When Counting Followers
     * @Then Proper Result With 0 Followers Is Returned
     */
    public function existingAuthorWithZeroFollowers(): void
    {
        $authorId = '1c22ed61-c305-44dd-a558-f261f434f583';
        $authorUsername = 'alice';
        $authorFollowers = 10;

        $redisMock = $this->createMock(\Redis::class);
        $redisMock
            ->expects($this->once())
            ->method('set')
            ->with(
                'author_followers_counter_projection:'.$authorId,
                json_encode(
                    [
                        'id' => $authorId,
                        'username' => $authorUsername,
                        'followers' => $authorFollowers
                    ]
                )
            )
        ;

        $dbMock = $this->buildEntityManagerMockReturning([
            'id' => $authorId,
            'username' => $authorUsername,
            'followers' => $authorFollowers
        ]);

        $handler = new CountFollowersProjectionHandler(
            $redisMock,
            $dbMock
        );

        $handler(
            CountFollowersProjection::ofAuthor($authorId)
        );
    }

    private function buildEntityManagerMockReturning($fakeReturn): EntityManagerInterface
    {
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

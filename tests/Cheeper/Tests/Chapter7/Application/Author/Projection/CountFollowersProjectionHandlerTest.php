<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Author\Projection;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepDate;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Cheep\CheepMessage;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandler;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class CountFollowersProjectionHandlerTest extends TestCase
{
    /**
     * @test
     * @Given
     * @When
     * @Then
     */
    public function nonExistingOrWithoutFollowers(): void
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
     * @atest
     * @Given
     * @When
     * @Then
     */
    public function authorExistingWithMoreThanOneFollowers(): void
    {
        $authorId = '1c22ed61-c305-44dd-a558-f261f434f583';

        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "1c22ed61-c305-44dd-a558-f261f434f583" does not exist');

        $redisMock = $this->createMock(\Redis::class);
//        $redisMock
//            ->expects($this->once())
//            ->method('set')
//        ;

        $dbMock = $this->buildEntityManagerMockReturning(false);

        $handler = new CountFollowersProjectionHandler(
            $redisMock,
            $dbMock
        );

        $handler(
            CountFollowersProjection::ofAuthor($authorId)
        );
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

<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRepositoriesAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter4\DomainModel\Author\Author;
use Cheeper\Chapter4\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRepositoriesAccess\CountFollowersQueryHandler;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use Cheeper\Chapter5\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter5\DomainModel\Follow\NumberOfFollowers;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CountFollowersQueryHandlerTest extends TestCase
{
    /** @test */
    public function givenNoExistingAuthorWhenCountingFollowersThenEmptyResultIsReturned(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "3409a21d-83b3-471e-a4f1-cf6748af65d2" does not exist');

        $nonExistingAuthorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $queryHandler = new CountFollowersQueryHandler(
            $this->buildFollowRepositoryMockReturning(null),
            $this->buildAuthorRepositoryMockReturning(null)
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
            $this->buildFollowRepositoryMockReturning(
                new NumberOfFollowers(
                    Uuid::fromString($authorId),
                    0
                )
            ),
            $this->buildAuthorRepositoryMockReturning(
                Author::signUp(
                    AuthorId::fromString($authorId),
                    UserName::pick('buenosvinos'),
                    EmailAddress::from('carlos.buenosvinos@gmail.com')
                )
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

    private function buildAuthorRepositoryMockReturning($fakeReturn) {
        $mock = $this->createStub(AuthorRepository::class);

        $mock->method('ofId')->willReturn(
            $fakeReturn
        );

        return $mock;
    }

    private function buildFollowRepositoryMockReturning($fakeReturn) {
        $mock = $this->createStub(FollowRepository::class);

        $mock->method('ofAuthorId')->willReturn(
            $fakeReturn
        );

        return $mock;
    }

    // @TODO: What happen if the connection is not right?
}

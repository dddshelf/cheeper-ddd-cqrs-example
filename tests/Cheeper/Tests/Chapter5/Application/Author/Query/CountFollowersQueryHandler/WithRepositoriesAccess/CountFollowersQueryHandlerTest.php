<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRepositoriesAccess;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\Chapter4\DomainModel\Author\Author;
use Cheeper\Chapter4\DomainModel\Author\AuthorRepository;
use Cheeper\Chapter4\DomainModel\Follow\FollowRepository;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersQuery;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\CountFollowersResponse;
use Cheeper\Chapter5\Application\Author\Query\CountFollowersQueryHandler\WithRepositoriesAccess\CountFollowersQueryHandler;
use PHPUnit\Framework\TestCase;

final class CountFollowersQueryHandlerTest extends TestCase
{
    /**
     * @test
     * @Given Non Existing Author
     * @When Counting Followers
     * @Then Non Existing Author Exception Should Be Thrown
     */
    public function nonExistingAuthor(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "3409a21d-83b3-471e-a4f1-cf6748af65d2" does not exist');

        $authorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $queryHandler = new CountFollowersQueryHandler(
            $this->buildFollowRepositoryMockReturning([]),
            $this->buildAuthorRepositoryMockReturning(null)
        );

        $queryHandler->__invoke(
            CountFollowersQuery::ofAuthor($authorId)
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
        $authorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $authorUsername = 'buenosvinos';
        $authorEmail = 'carlos.buenosvinos@gmail.com';
        $authorFollowers = 0;

        $queryHandler = new CountFollowersQueryHandler(
            $this->buildFollowRepositoryMockReturning([]),
            $this->buildAuthorRepositoryMockReturning(
                $this->buildSampleAuthor($authorId, $authorUsername, $authorEmail)
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

    private function buildAuthorRepositoryMockReturning(?Author $fakeReturn): AuthorRepository
    {
        $mock = $this->createStub(AuthorRepository::class);

        $mock->method('ofId')->willReturn(
            $fakeReturn
        );

        return $mock;
    }

    private function buildFollowRepositoryMockReturning(array $fakeReturn): FollowRepository
    {
        $mock = $this->createStub(FollowRepository::class);

        $mock->method('fromAuthorId')->willReturn(
            $fakeReturn
        );

        return $mock;
    }

    private function buildSampleAuthor(
        string $authorId,
        string $authorUsername,
        string $authorEmail
    ): Author {
        return Author::signUp(
            AuthorId::fromString($authorId),
            UserName::pick($authorUsername),
            EmailAddress::from($authorEmail)
        );
    }
}

<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CheepApplicationService;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Author\EmailAddress;
use Cheeper\DomainModel\Author\UserName;
use Cheeper\DomainModel\Cheep\Cheep;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepMessage;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use Cheeper\Tests\DomainModel\Cheep\CheepTestDataBuilder;
use Mockery;
use PHPUnit\Framework\TestCase;

final class CheepApplicationServiceTest extends TestCase
{
    private CheepRepository $cheepRepository;
    private AuthorRepository $authorRepository;
    private CheepApplicationService $cheepService;

    public function setUp(): void
    {
        $this->cheepRepository = Mockery::mock(CheepRepository::class);
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->cheepService = new CheepApplicationService($this->authorRepository, $this->cheepRepository);
    }

    /** @test */
    public function itRaisesExceptionWhenAuthorNotFound(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->cheepService->postCheep('irrelevant', 'irrelevant');
    }

    /** @test */
    public function itShouldAddCheep(): void
    {
        $this->authorRepository->add(
            AuthorTestDataBuilder::anAuthor()->build()
        );

        $this->cheepRepository->expects('add');

        $cheep = $this->cheepService->postCheep('irrelevant', 'message');

        $this->assertNotNull($cheep);
        $this->assertNotNull($cheep->authorId());
        $this->assertEquals('message', $cheep->cheepMessage()->message());
    }

    /** @test */
    public function givenATimelineRequestWhenTheAuthorDoesNotExistThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->cheepService->timelineFrom(AuthorTestDataBuilder::anAuthorIdentity()->toString(), 0, 1);
    }

    /** @test */
    public function givenATimelineRequestWhenExecutionGoesWellThenAListOfCheepsShouldBeReturned(): void
    {
        $author = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);

        $cheeps = [
            CheepTestDataBuilder::aCheep()->withAMessage("test1"),
            CheepTestDataBuilder::aCheep()->withAMessage("test2"),
            CheepTestDataBuilder::aCheep()->withAMessage("test3"),
        ];

        $this->cheepRepository->allows()->ofFollowingPeopleOf($author, 0, 10)->andReturn($cheeps);

        $this->assertCount(
            count($cheeps),
            $this->cheepService->timelineFrom($author->authorId()->toString(), 0, 10)
        );
    }
}

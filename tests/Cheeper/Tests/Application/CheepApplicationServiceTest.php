<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CheepApplicationService;
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

final class CheepApplicationServiceTest extends TestCase
{
    private CheepRepository $cheepRepository;
    private AuthorRepository $authorRepository;
    private CheepApplicationService $cheepService;

    public function setUp(): void
    {
        $this->cheepRepository = new InMemoryCheepRepository();
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
        $author = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);

        $cheep = $this->cheepService->postCheep($author->userName()->toString(), 'message');

        // Retrieve cheep by ID in order to make sure it has been persisted into the persistence store
        $cheep = $this->cheepService->getCheep($cheep->cheepId()->toString());

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
            CheepTestDataBuilder::aCheep()->withAMessage("test1")->build(),
            CheepTestDataBuilder::aCheep()->withAMessage("test2")->build(),
            CheepTestDataBuilder::aCheep()->withAMessage("test3")->build(),
        ];

        Iter\apply($cheeps, fn(Cheep $c) => $this->cheepRepository->add($c));

        $this->assertCount(
            count($cheeps),
            $this->cheepService->timelineFrom($author->authorId()->toString(), 0, 10)
        );
    }
}

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
    private AuthorRepository $authorRepository;
    private CheepApplicationService $cheepService;

    public function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->cheepService = new CheepApplicationService($this->authorRepository, new InMemoryCheepRepository());
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

        $cheep = $this->cheepService->postCheep($author->userName()->userName, 'message');

        // Retrieve cheep by ID in order to make sure it has been persisted into the persistence store
        $cheep = $this->cheepService->getCheep($cheep->cheepId()->id);

        $this->assertNotNull($cheep);
        $this->assertNotNull($cheep->authorId());
        $this->assertEquals('message', $cheep->cheepMessage()->message);
    }
}

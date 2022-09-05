<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\PostCheep\PostCheepCommand;
use Cheeper\Application\PostCheep\PostCheepCommandHandler;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryCheepRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use Cheeper\Tests\DomainModel\Cheep\CheepTestDataBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class PostCheepCommandHandlerTest extends TestCase
{
    private AuthorRepository $authorRepository;
    private CheepRepository $cheepRepository;
    private PostCheepCommandHandler $postCheepCommandHandler;

    public function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->postCheepCommandHandler = new PostCheepCommandHandler($this->authorRepository, $this->cheepRepository);
    }

    /** @test */
    public function itRaisesExceptionWhenAuthorNotFound(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        ($this->postCheepCommandHandler)(
            new PostCheepCommand(
                Uuid::uuid6()->toString(),
                'irrelevant',
                'irrelevant'
            )
        );
    }

    /** @test */
    public function itShouldAddCheep(): void
    {
        $author = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);

        $cheepId = Uuid::uuid6();

        ($this->postCheepCommandHandler)(
            new PostCheepCommand(
                $cheepId->toString(),
                'irrelevant',
                'irrelevant'
            )
        );

        $cheep = $this->cheepRepository->ofId(
            CheepTestDataBuilder::aCheepIdentity($cheepId)
        );

        $this->assertNotNull($cheep);
    }
}
<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\Follow\FollowCommand;
use Cheeper\Application\Follow\FollowCommandHandler;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Follow\FollowRepository;
use Cheeper\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Infrastructure\Persistence\InMemoryAuthorRepository;
use Cheeper\Infrastructure\Persistence\InMemoryFollowRepository;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use PHPUnit\Framework\TestCase;

final class FollowCommandHandlerTest extends TestCase
{
    private AuthorRepository $authorRepository;
    private FollowRepository $followRepository;
    private InMemoryEventBus $eventBus;
    private FollowCommandHandler $followCommandHandler;

    protected function setUp(): void
    {
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();
        $this->eventBus = new InMemoryEventBus();

        $this->followCommandHandler = new FollowCommandHandler($this->authorRepository, $this->followRepository, $this->eventBus);
    }

    /** @test */
    public function givenANonExistingAuthorWhenFollowUseCaseIsExecutedThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->follow(
            AuthorTestDataBuilder::anAuthor()->build(),
            AuthorTestDataBuilder::anAuthor()->build(),
        );
    }

    /** @test */
    public function givenANonExistingAuthorToFollowWhenFollowUseCaseIsExecutedThenAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $author = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($author);

        $this->follow(
            $author,
            AuthorTestDataBuilder::anAuthor()->build(),
        );
    }

    /** @test */
    public function itCreatesAFollowRelationshipBetweenAuthors(): void
    {
        $fromAuthor = AuthorTestDataBuilder::anAuthor()->build();
        $toAuthor = AuthorTestDataBuilder::anAuthor()->build();

        $this->authorRepository->add($fromAuthor);
        $this->authorRepository->add($toAuthor);

        $this->follow($fromAuthor, $toAuthor);

        $follows = $this->followRepository->toAuthorId($toAuthor->authorId());

        $this->assertCount(1, $follows);
        $this->assertNotEmpty($this->eventBus->getEvents());
    }

    private function follow(Author $fromAuthor, Author $toAuthor): void
    {
        ($this->followCommandHandler)(
            new FollowCommand($fromAuthor->authorId()->id, $toAuthor->authorId()->id)
        );
    }
}
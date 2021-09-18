<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Cheep;

use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\CheepPosted;
use Cheeper\DomainModel\Cheep\Cheeps;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\Infrastructure\Persistence\InMemoryCheeps;
use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use Cheeper\Tests\Helper\SendsCommands;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

//snippet post-cheep-handler-test
final class PostCheepHandlerTest extends TestCase
{
    use SendsCommands;

    /** @test */
    public function throwsExceptionWhenAuthorDoesNotExist(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "b547cf31-a0d2-4d26-aa77-8901fbdc0549" does not exist');

        $authorId = 'b547cf31-a0d2-4d26-aa77-8901fbdc0549';
        $cheepId = Uuid::uuid4()->toString();

        $this->postNewCheep(
            $authorId,
            $cheepId,
            'A message'
        );
    }

    /** @test */
    public function throwsExceptionWhenAuthorIdIsNotUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(
            'not-an-uuid',
            Uuid::uuid4()->toString(),
            'A message'
        );
    }

    /** @test */
    public function throwsExceptionWhenCheepIdIsNotUuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(
            Uuid::uuid4()->toString(),
            'not-an-uuid',
            'A message'
        );
    }

    /** @test */
    public function throwsExceptionWhenCheepMessageIsEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->postNewCheep(
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
            ''
        );
    }

    /** @test */
    public function cheepIsPersistedSuccessfully(): void
    {
        $author = AuthorTestDataBuilder::anAuthor()->build();
        $this->authors->add($author);

        $cheepId = Uuid::uuid4()->toString();

        $this->postNewCheep(
            $author->authorId()->id(),
            $cheepId,
            'A message'
        );

        $cheep = $this->cheeps->ofId(CheepId::fromString($cheepId));
        $this->assertNotNull($cheep);

        $events = $this->eventBus->events();
        $this->assertCount(1, $events);
        $this->assertSame(CheepPosted::class, $events[0]::class);
    }

    private function postNewCheep(
        string $authorId,
        string $cheepId,
        string $message
    ): void {
        (new PostCheepHandler(
            $this->authors,
            $this->cheeps,
            $this->eventBus
        ))(
            PostCheep::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }
}
//end-snippet

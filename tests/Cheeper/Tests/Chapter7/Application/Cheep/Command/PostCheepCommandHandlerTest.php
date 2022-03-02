<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter7\Application\Cheep\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Author\EmailAddress;
use Cheeper\AllChapters\DomainModel\Author\UserName;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\Chapter7\Application\Cheep\Command\PostCheepCommand;
use Cheeper\Chapter7\Application\Cheep\Command\PostCheepCommandHandler;
use Cheeper\Chapter7\DomainModel\Author\Author;
use Cheeper\Chapter7\DomainModel\Cheep\CheepPosted;
use Cheeper\Chapter7\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Chapter7\Infrastructure\DomainModel\Author\InMemoryAuthorRepository;
use Cheeper\Chapter7\Infrastructure\DomainModel\Cheep\InMemoryCheepRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use function Functional\first;

//snippet post-cheep-handler-test
final class PostCheepCommandHandlerTest extends TestCase
{
    private InMemoryCheepRepository $cheepRepository;
    private InMemoryAuthorRepository $authorRepository;
    private InMemoryEventBus $eventBus;

    protected function setUp(): void
    {
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->eventBus = new InMemoryEventBus();
    }

    /**
     * @test
     */
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
        $authorId = '3409a21d-83b3-471e-a4f1-cf6748af65d2';
        $authorUsername = 'buenosvinos';
        $authorEmail = 'carlos.buenosvinos@gmail.com';
        $author = $this->buildSampleAuthor($authorId, $authorUsername, $authorEmail);
        $this->authorRepository->add($author);

        $cheepId = Uuid::uuid4()->toString();

        $this->postNewCheep(
            $author->authorId()->id(),
            $cheepId,
            'A message'
        );

        $cheep = $this->cheepRepository->ofId(CheepId::fromString($cheepId));
        $this->assertNotNull($cheep);

        $events = $this->eventBus->events();

        /** @var CheepPosted $cheepPosted */
        $cheepPosted = first($events);
        $this->assertCount(1, $events);
        $this->assertSame(CheepPosted::class, $cheepPosted::class);
        $this->assertSame($authorId, $cheepPosted->authorId());
        $this->assertSame($cheepId, $cheepPosted->cheepId());
    }

    private function postNewCheep(
        string $authorId,
        string $cheepId,
        string $message
    ): void {
        $this->eventBus->reset();

        (new PostCheepCommandHandler(
            $this->authorRepository,
            $this->cheepRepository,
            $this->eventBus
        ))(
            PostCheepCommand::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }

    private function buildSampleAuthor(string $authorId, string $authorUsername, string $authorEmail): Author
    {
        return Author::signUp(
            AuthorId::fromString($authorId),
            UserName::pick($authorUsername),
            EmailAddress::from($authorEmail)
        );
    }
}
//end-snippet

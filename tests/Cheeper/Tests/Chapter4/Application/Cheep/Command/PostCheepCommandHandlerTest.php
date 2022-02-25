<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Cheep\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Cheep\CheepId;
use Cheeper\AllChapters\DomainModel\Clock;
use Cheeper\AllChapters\DomainModel\Clock\DateCollectionClockStrategy;
use Cheeper\Chapter4\DomainModel\Cheep\CheepPosted;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommand;
use Cheeper\Chapter4\Application\Cheep\Command\PostCheepCommandHandler;
use Cheeper\Chapter4\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Chapter4\Infrastructure\DomainModel\Author\InMemoryAuthorRepository;
use Cheeper\Chapter4\Infrastructure\DomainModel\Cheep\InMemoryCheepRepository;
use Cheeper\Tests\AllChapters\DomainModel\Author\AuthorTestDataBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use function Functional\first;

//snippet post-cheep-handler-test
final class PostCheepCommandHandlerTest extends TestCase
{
    private InMemoryCheepRepository $cheepRepository;
    private InMemoryAuthorRepository $authorRepository;
    private InMemoryEventBus $eventBus;
    private DateTimeImmutable $today;

    protected function setUp(): void
    {
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->eventBus = new InMemoryEventBus();
        $this->today = $this->getToday();

        Clock::instance()->changeStrategy(
            new DateCollectionClockStrategy([$this->today])
        );
    }

    protected function getToday(): DateTimeImmutable
    {
        return new DateTimeImmutable(
            'now', new \DateTimeZone('UTC')
        );
    }

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
        $this->authorRepository->add($author);

        $cheepId = Uuid::uuid4()->toString();

        $message = 'A message';
        $this->postNewCheep(
            $author->authorId()->id(),
            $cheepId,
            $message
        );

        $cheep = $this->cheepRepository->ofId(CheepId::fromString($cheepId));
        $this->assertNotNull($cheep);

        $events = $this->eventBus->events();
        /** @var CheepPosted $cheepPosted */
        $cheepPosted = first($events);
        $this->assertCount(1, $events);
        $this->assertSame(CheepPosted::class, $cheepPosted::class);
        $this->assertSame($author->authorId()->toString(), $cheepPosted->authorId());
        $this->assertSame($cheepId, $cheepPosted->cheepId());
        $this->assertSame($message, $cheepPosted->cheepMessage());
        $this->assertSame($this->today->format(DATE_ATOM), $cheepPosted->cheepDate());
        $this->assertSame($this->today, $cheepPosted->occurredOn());
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
}
//end-snippet

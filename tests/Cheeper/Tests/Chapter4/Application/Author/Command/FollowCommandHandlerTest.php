<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter4\Application\Author\Command;

use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter4\Application\Author\Command\FollowCommand;
use Cheeper\Chapter4\Application\Author\Command\FollowCommandHandler;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommandHandler;
use Cheeper\Chapter4\Application\Author\Command\SignUpWithoutEvents\SignUpCommand;
use Cheeper\Chapter4\Infrastructure\Application\InMemoryEventBus;
use Cheeper\Chapter4\Infrastructure\DomainModel\Author\InMemoryAuthorRepository;
use Cheeper\Chapter4\Infrastructure\DomainModel\Cheep\InMemoryCheepRepository;
use Cheeper\Chapter4\Infrastructure\DomainModel\Follow\InMemoryFollowRepository;
use Cheeper\Tests\Helper\SendsCommands;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class FollowCommandHandlerTest extends TestCase
{
    private InMemoryCheepRepository $cheepRepository;
    private InMemoryAuthorRepository $authorRepository;
    private InMemoryEventBus $eventBus;
    private InMemoryFollowRepository $followRepository;

    protected function setUp(): void
    {
        $this->cheepRepository = new InMemoryCheepRepository();
        $this->authorRepository = new InMemoryAuthorRepository();
        $this->followRepository = new InMemoryFollowRepository();
        $this->eventBus = new InMemoryEventBus();
    }

    /** @test */
    public function whenFollowerDoesNotExistAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "abcd" does not exist');

        $this->followAuthor('abcd', 'dcba');
    }

    /** @test */
    public function whenAuthorFollowedDoesNotExistAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "test2" does not exist');

        $this->signUpAuthorWith(
            Uuid::uuid4()->toString(),
            'test',
            'test@email.com',
            'test',
            'test',
            'test',
            'http://google.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $this->followAuthor('test', 'test2');
    }

    private function signUpAuthorWith(string $authorId, string $userName, string $email, string $name, string $biography, string $location, string $website, string $birthDate): void
    {
        (new SignUpCommandHandler(
            $this->authorRepository
        ))(
            new SignUpCommand(
                $authorId,
                $userName,
                $email,
                $name,
                $biography,
                $location,
                $website,
                $birthDate
            )
        );
    }

    /** @test */
    public function authorsCanFollowOtherAuthors(): void
    {
        $authorId = Uuid::uuid4();

        $this->signUpAuthorWith(
            $authorId->toString(),
            'test',
            'test@gmail.com',
            'test',
            'test',
            'test',
            'https://google.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $followedId = Uuid::uuid4();

        $this->signUpAuthorWith(
            $followedId->toString(),
            'test2',
            'test2@gmail.com',
            'test2',
            'test2',
            'test2',
            'https://bing.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $this->followAuthor('test', 'test2');

        $followers = $this->followRepository->numberOfFollowersFor(AuthorId::fromUuid($authorId));
        $this->assertSame(1, $followers);
    }

    private function followAuthor(string $followee, string $followed): void
    {
        (new FollowCommandHandler($this->authorRepository, $this->followRepository))(
            FollowCommand::anAuthor($followed, $followee)
        );
    }
}

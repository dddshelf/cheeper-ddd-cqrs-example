<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Author;

use Cheeper\Application\Command\Author\Follow;
use Cheeper\Application\Command\Author\FollowHandler;
use Cheeper\Chapter6\Infrastructure\Application\Event\InMemoryEventBus;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class FollowHandlerTest extends TestCase
{
    private InMemoryAuthors $authorsRepository;
    private InMemoryEventBus $eventBus;

    protected function setUp(): void
    {
        $this->authorsRepository = new InMemoryAuthors();
        $this->eventBus = new InMemoryEventBus();
    }

    /** @test */
    public function whenFollowerDoesNotExistAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);
        $this->expectExceptionMessage('Author "abcd" does not exist');

        $this->followAuthor('abcd', 'dcba');
    }

    private function followAuthor(string $followee, string $followed): void
    {
        (new FollowHandler($this->authorsRepository))(
            Follow::anAuthor($followed, $followee)
        );
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
            'http://google.com/',
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
            'http://bing.com/',
            (new DateTimeImmutable())->format('Y-m-d')
        );

        $this->followAuthor('test', 'test2');

        $author = $this->authors->ofId(AuthorId::fromUuid($authorId));
        $this->assertCount(1, $author->following());
    }
}

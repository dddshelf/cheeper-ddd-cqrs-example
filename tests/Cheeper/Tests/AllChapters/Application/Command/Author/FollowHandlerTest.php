<?php

declare(strict_types=1);

namespace Cheeper\Tests\AllChapters\Application\Command\Author;

use Cheeper\AllChapters\Application\Command\Author\Follow;
use Cheeper\AllChapters\Application\Command\Author\FollowHandler;
use Cheeper\AllChapters\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\AllChapters\Infrastructure\Persistence\InMemoryFollows;
use Cheeper\Tests\Helper\SendsCommands;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class FollowHandlerTest extends TestCase
{
    use SendsCommands;

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

        $followers = $this->follows->numberOfFollowersFor(AuthorId::fromUuid($authorId));
        $this->assertSame(1, $followers);
    }
}
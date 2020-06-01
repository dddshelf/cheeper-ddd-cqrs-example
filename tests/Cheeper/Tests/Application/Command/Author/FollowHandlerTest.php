<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Author;

use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Author\AuthorId;
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

        $this->followAuthor('test', 'test');
    }

    /** @test */
    public function whenAuthorFollowedDoesNotExistAnExceptionShouldBeThrown(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->signUpAuthorWith(
            Uuid::uuid4()->toString(),
            'test',
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

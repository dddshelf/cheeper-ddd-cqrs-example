<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application\Command\Cheep;

use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Cheeper\DomainModel\Cheep\CheepId;
use Cheeper\DomainModel\Cheep\Cheeps;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\Authors;
use Cheeper\Infrastructure\Persistence\InMemoryAuthors;
use Cheeper\Infrastructure\Persistence\InMemoryCheeps;
use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\Tests\DomainModel\Author\AuthorTestDataBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

//snippet post-cheep-handler-test
final class PostCheepHandlerTest extends TestCase
{
    private Authors $authors;
    private Cheeps $cheeps;

    /** @before */
    protected function setUp(): void
    {
        $this->authors = new InMemoryAuthors();
        $this->cheeps = new InMemoryCheeps();
    }

    /** @test */
    public function throwsExceptionWhenAuthorDoesNotExist(): void
    {
        $this->expectException(AuthorDoesNotExist::class);

        $this->postNewCheep(
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
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
        $this->authors->save($author);
        $cheepId = Uuid::uuid4()->toString();

        $this->postNewCheep($author->userId()->id(), $cheepId, 'A message');

        $cheep = $this->cheeps->ofId(CheepId::fromString($cheepId));
        $this->assertNotNull($cheep);
    }

    private function postNewCheep(
        string $authorId,
        string $cheepId,
        string $message
    ): void {
        (new PostCheepHandler($this->authors, $this->cheeps))(
            PostCheep::fromArray([
                'author_id' => $authorId,
                'cheep_id' => $cheepId,
                'message' => $message,
            ])
        );
    }
}
//end-snippet

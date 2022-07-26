<?php

declare(strict_types=1);

namespace Cheeper\Tests\Application;

use Cheeper\Application\CheepService;
use Cheeper\DomainModel\Author\Author;
use Cheeper\DomainModel\Author\AuthorRepository;
use Cheeper\DomainModel\Cheep\CheepRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

final class CheepServiceTest extends TestCase
{
    private CheepRepository $cheeps;
    private AuthorRepository $authors;
    private CheepService $cheepService;

    public function setUp(): void
    {
        $this->cheeps = Mockery::mock(CheepRepository::class);
        $this->authors = Mockery::mock(AuthorRepository::class);
        $this->cheepService = new CheepService($this->authors, $this->cheeps);
    }

    /** @test */
    public function itRaisesExceptionWhenAuthorNotFound(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->authors->allows('ofUserName')->andReturns(null);

        $this->cheepService->postCheep('irrelevant', 'irrelevant');
    }

    /** @test */
    public function itShouldAddCheep(): void
    {
        $this->authors->allows('ofUsername')->andReturns(self::anAuthor());
        $this->cheeps->expects('add');

        $cheep = $this->cheepService->postCheep('irrelevant', 'message');

        $this->assertNotNull($cheep);
        $this->assertEquals(1, $cheep->authorId());
        $this->assertEquals('message', $cheep->message());
    }

    private static function anAuthor(): Author
    {
        $author = Author::create('irrelevant');
        $author->setId(1);

        return $author;
    }
}

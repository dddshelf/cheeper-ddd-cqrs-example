<?php

declare(strict_types=1);

namespace Cheeper\Tests\Chapter2;

use Cheeper\Chapter2\Author;
use Cheeper\Chapter2\Hexagonal\Application\CheepService;
use Cheeper\Chapter2\Hexagonal\DomainModel\AuthorRepository;
use Cheeper\Chapter2\Hexagonal\DomainModel\CheepRepository;
use PHPUnit\Framework\TestCase;

//snippet cheep-service-test
final class CheepServiceTest extends TestCase
{
    private CheepRepository $cheeps;
    private AuthorRepository $authors;
    private CheepService $cheepService;

    public function setUp(): void
    {
        $this->cheeps = \Mockery::mock(CheepRepository::class);
        $this->authors = \Mockery::mock(AuthorRepository::class);
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
//end-snippet

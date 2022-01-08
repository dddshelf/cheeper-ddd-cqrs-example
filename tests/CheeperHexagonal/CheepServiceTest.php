<?php

declare(strict_types=1);

namespace CheeperHexagonal;

use PHPUnit\Framework\TestCase;

use CheeperLayered\Author;
use CheeperLayered\Authors;
use CheeperLayered\Cheeps;

//snippet cheep-service-test
final class CheepServiceTest extends TestCase
{
    private Cheeps $cheeps;
    private Authors $authors;
    private CheepService $cheepService;

    public function setUp(): void
    {
        $this->cheeps = \Mockery::mock(Cheeps::class);
        $this->authors = \Mockery::mock(Authors::class);
        $this->cheepService = new CheepService($this->authors, $this->cheeps);
    }

    /** @test */
    public function itRaisesExceptionWhenAuthorNotFound(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->authors->allows('byUsername')->andReturns(null);

        $this->cheepService->postCheep('irrelevant', 'irrelevant');
    }

    /** @test */
    public function itShouldAddCheep(): void
    {
        $this->authors->allows('byUsername')->andReturns(self::anAuthor());
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

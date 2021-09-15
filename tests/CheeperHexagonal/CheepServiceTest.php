<?php

namespace CheeperHexagonal;

use PHPUnit\Framework\TestCase;

use CheeperLayered\Author;
use CheeperLayered\Authors;
use CheeperLayered\Cheeps;

//snippet cheep-service-test
class CheepServiceTest extends TestCase
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

    /**
     * @test
     */
    public function itRaisesExceptionWhenAuthorNotFound(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->authors->shouldReceive('byUsername')->andReturns(null);

        $this->cheepService->postCheep('irrelevant', 'irrelevant');
    }

    /**
     * @test
     */
    public function itShouldAddCheep(): void
    {
        $this->authors->shouldReceive('byUsername')->andReturns(self::anAuthor(1));
        $this->cheeps->shouldReceive('add')->once();

        $cheep = $this->cheepService->postCheep('irrelevant', 'message');

        $this->assertNotNull($cheep);
        $this->assertEquals(1, $cheep->authorId());
        $this->assertEquals('message', $cheep->message());
    }

    private static function anAuthor(int $id): Author
    {
        $author = Author::create('irrelevant');
        $author->setId($id);

        return $author;
    }
}
//end-snippet
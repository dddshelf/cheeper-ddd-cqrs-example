<?php

declare(strict_types=1);

namespace Architecture\CQRS\Domain;

use PHPUnit\Framework\TestCase;

final class PostTest extends TestCase
{
    /** @test */
    public function itShouldCreatePost(): void
    {
        $p = Post::writeNewFrom('A title', 'Some content');

        $this->assertEquals('A title', $p->title());
        $this->assertEquals('Some content', $p->content());
        $this->assertEquals([], $p->categories());
        $this->assertFalse($p->isPublished());
    }

    /** @test */
    public function itShouldChangeTitle(): void
    {
        $p = $this->aPost();

        $p->changeTitleFor('Another title');

        $this->assertEquals('Another title', $p->title());
    }

    private function aPost(): Post
    {
        return Post::writeNewFrom('A title', 'Some content');
    }

    /** @test */
    public function itShouldChangeContent(): void
    {
        $p = $this->aPost();

        $p->changeContentFor('Another content');

        $this->assertEquals('Another content', $p->content());
    }

    /** @test */
    public function itShouldPublish(): void
    {
        $p = $this->aPost();

        $p->publish();

        $this->assertTrue($p->isPublished());
    }

    /** @test */
    public function itShouldCategorize(): void
    {
        $p = $this->aPost();

        $p->categorizeIn($c = CategoryId::create());

        $this->assertSame($p->categories()[0]->id(), $c->id());
    }

    /** @test */
    public function itShouldNotCategorizeSameCategoryTwice(): void
    {
        $p = $this->aPost();

        $p->categorizeIn($c = CategoryId::create());
        $p->categorizeIn($c);

        $this->assertSame(count($p->categories()), 1);
        $this->assertSame($p->categories()[0]->id(), $c->id());
    }
}

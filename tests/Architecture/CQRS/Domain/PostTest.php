<?php

namespace Architecture\CQRS\Domain;

use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreatePost()
    {
        $p = Post::writeNewFrom('A title', 'Some content');

        $this->assertEquals('A title', $p->title());
        $this->assertEquals('Some content', $p->content());
        $this->assertEquals([], $p->categories());
        $this->assertFalse($p->isPublished());
    }

    /**
     * @test
     */
    public function itShouldChangeTitle()
    {
        $p = $this->aPost();

        $p->changeTitleFor('Another title');

        $this->assertEquals('Another title', $p->title());
    }

    private function aPost(): Post
    {
        return Post::writeNewFrom('A title', 'Some content');
    }

    /**
     * @test
     */
    public function itShouldChangeContent()
    {
        $p = $this->aPost();

        $p->changeContentFor('Another content');

        $this->assertEquals('Another content', $p->content());
    }

    /**
     * @test
     */
    public function itShouldPublish()
    {
        $p = $this->aPost();

        $p->publish();

        $this->assertTrue($p->isPublished());
    }

    /**
     * @test
     */
    public function itShouldCategorize()
    {
        $p = $this->aPost();

        $p->categorizeIn($c = CategoryId::create());

        $this->assertTrue($p->categories()[0]->id() === $c->id());
    }

    /**
     * @test
     */
    public function itShouldNotCategorizeSameCategoryTwice()
    {
        $p = $this->aPost();

        $p->categorizeIn($c = CategoryId::create());
        $p->categorizeIn($c);

        $this->assertTrue(count($p->categories()) === 1);
        $this->assertTrue($p->categories()[0]->id() === $c->id());
    }
}

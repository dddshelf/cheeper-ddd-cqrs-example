<?php

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesAnAuthor(): void
    {
        $author = Author::create('example');

        $this->assertNotNull($author);
        $this->assertEquals($author->username(), 'example');
        $this->assertNull($author->bio());
        $this->assertNull($author->website());
    }

    /**
     * @test
     */
    public function itUpdatesWebsite(): void
    {
        $author = Author::create('example');
        
        $author->setWebsite('https://example.com');

        $this->assertEquals($author->website(), 'https://example.com');
    }

    /**
     * @test
     */
    public function itUpdatesBio(): void
    {
        $author = Author::create('example');
        
        $author->setBio('Amazing bio');

        $this->assertEquals($author->bio(), 'Amazing bio');
    }
}

<?php

declare(strict_types=1);

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

final class AuthorTest extends TestCase
{
    /** @test */
    public function itCreatesAnAuthor(): void
    {
        $author = Author::create('example');

        $this->assertNotNull($author);
        $this->assertEquals('example', $author->username());
        $this->assertNull($author->bio());
        $this->assertNull($author->website());
    }

    /** @test */
    public function itUpdatesWebsite(): void
    {
        $author = Author::create('example');

        $author->setWebsite('https://example.com');

        $this->assertEquals('https://example.com', $author->website());
    }

    /** @test */
    public function itUpdatesBio(): void
    {
        $author = Author::create('example');

        $author->setBio('Amazing bio');

        $this->assertEquals('Amazing bio', $author->bio());
    }
}

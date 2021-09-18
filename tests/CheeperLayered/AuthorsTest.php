<?php

declare(strict_types=1);

namespace CheeperLayered;

final class AuthorsTest extends DatabaseTestCase
{
    /** @test */
    public function itShouldPersistAuthor(): void
    {
        $author = Author::create('johndoe');
        $author->setWebsite('https://example.com');
        $author->setBio('Awesome bio');
        $authors = new Authors();

        $authors->save($author);

        $a = $authors->byId($author->id());
        $this->assertNotNull($a);
        $this->assertEquals('johndoe', $author->username());
        $this->assertEquals('https://example.com', $author->website());
        $this->assertEquals('Awesome bio', $author->bio());
    }

    /** @test */
    public function itShouldFindByUsername(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $a = (new Authors())->byUsername('johndoe');

        $this->assertNotNull($a);
    }
}

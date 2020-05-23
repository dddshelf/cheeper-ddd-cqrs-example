<?php

namespace CheeperLayered;

class AuthorsTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function itShouldPersistAuthor(): void
    {
        $author = Author::create('johndoe');
        $author->setWebsite('https://example.com');
        $author->setBio('Awesome bio');
        $authors = new Authors();

        $authors->save($author);

        $a = $authors->byId($author->id());
        $this->assertNotNull($a);
        $this->assertEquals($author->username(), 'johndoe');
        $this->assertEquals($author->website(), 'https://example.com');
        $this->assertEquals($author->bio(), 'Awesome bio');
    }

    /**
     * @test
     */
    public function itShouldFindByUsername(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $a = (new Authors())->byUsername('johndoe');

        $this->assertNotNull($a);
    }
}

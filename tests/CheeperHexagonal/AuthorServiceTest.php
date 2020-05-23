<?php

namespace CheeperHexagonal;

use CheeperLayered\Authors;
use CheeperLayered\DatabaseTestCase;

class AuthorServiceTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function itShouldUpdateAuthor(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $c = (new AuthorService(new Authors()))
            ->update(1, 'doejohn', 'http://example.com', 'Some bio');

        $this->assertNotNull($c);
    }
}

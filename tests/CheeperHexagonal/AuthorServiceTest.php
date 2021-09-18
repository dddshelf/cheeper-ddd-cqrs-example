<?php

declare(strict_types=1);

namespace CheeperHexagonal;

use CheeperLayered\Authors;
use CheeperLayered\DatabaseTestCase;

final class AuthorServiceTest extends DatabaseTestCase
{
    /** @test */
    public function itShouldUpdateAuthor(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $c = (new AuthorService(new Authors()))
            ->update(1, 'doejohn', 'https://example.com', 'Some bio');

        $this->assertNotNull($c);
    }
}

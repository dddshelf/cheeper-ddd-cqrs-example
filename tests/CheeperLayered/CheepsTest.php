<?php

namespace CheeperLayered;

class CheepsTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function itShouldPublishCheep(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $c = Cheep::compose(1, 'A message');

        (new Cheeps())->add($c);

        $this->assertNotNull($c->id());
    }
}

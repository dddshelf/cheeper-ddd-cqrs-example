<?php

namespace CheeperLayered;

class CheepServiceTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function itShouldPostCheep(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $c = (new CheepService())->postCheep('johndoe', 'A message');

        $this->assertNotNull($c);
    }
}

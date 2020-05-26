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

    /**
     * @test
     */
    public function itShouldFetchAuthorTimeline(): void
    {
        $this->exec(<<<SQL
            INSERT INTO authors (id, username) VALUES
                (1, 'johndoe'),
                (2, 'rosemary');

            INSERT INTO follows (followee_id, follower_id) VALUES
                (1, 2);

            INSERT INTO cheeps (author_id, message, date) VALUES
                (1, "Hello, I'm John!", "2020-05-26 00:21:32"),
                (2, "Hello, I'm Rose!", "2020-05-26 00:18:29");
        SQL);

        $t = (new Cheeps())->timelineOf(2);

        $this->assertCheepEquals($t[0], 1, "Hello, I'm John!", "2020-05-26 00:21:32");
        $this->assertCheepEquals($t[1], 2, "Hello, I'm Rose!", "2020-05-26 00:18:29");
    }

    private function assertCheepEquals(
        Cheep $cheep,
        int $id,
        string $message,
        string $date
    ): void
    {
        $this->assertEquals($id, $cheep->id());
        $this->assertEquals($message, $cheep->message());
        $this->assertEquals($date, $cheep->date()->format('Y-m-d H:i:s'));
    }
}

<?php

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

class CheepsTest extends TestCase
{
    private \PDO $db;
    private Cheeps $cheeps;

    public function setUp(): void
    {
        $this->db = new \PDO(
            'mysql:host=127.0.0.1;dbname=db',
            'user',
            'pass',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        );

        $this->cheeps = new Cheeps();
    }

    /**
     * @test
     */
    public function itShouldPublishCheep(): void
    {
        $this->db->exec(<<<SQL
            DROP TABLE IF EXISTS cheeps;
            DROP TABLE IF EXISTS authors;
            
            CREATE TABLE authors (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                username VARCHAR(100) NOT NULL,
                PRIMARY KEY (id)
            );

            CREATE TABLE cheeps (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                author_id INT(11) UNSIGNED NOT NULL,
                message VARCHAR(240) NOT NULL,
                date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES authors(id),
                PRIMARY KEY (id)
            );

            INSERT INTO authors (id, username) VALUES (1, 'johndoe');
        SQL);

        $c = Cheep::compose(1, 'A message');

        $this->cheeps->add($c);

        $this->assertNotNull($c->id());

        $this->db->exec(<<<SQL
            DROP TABLE cheeps;
            DROP TABLE authors;
        SQL);
    }
}

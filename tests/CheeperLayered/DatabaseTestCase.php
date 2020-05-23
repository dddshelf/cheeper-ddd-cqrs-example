<?php

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

class DatabaseTestCase extends TestCase
{
    private \PDO $db;

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

        $this->db->beginTransaction();

        $this->db->exec(<<<SQL
            DROP TABLE IF EXISTS cheeps;
            DROP TABLE IF EXISTS authors;
            
            CREATE TABLE authors (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                username VARCHAR(100) NOT NULL,
                website VARCHAR(255) NULL,
                bio VARCHAR(255) NULL,
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
        SQL);
    }

    protected function exec(string $sql): void
    {
        $this->db->exec($sql);
    }

    public function tearDown(): void
    {
        $this->db->rollBack();
    }
}

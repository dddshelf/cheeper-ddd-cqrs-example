<?php

declare(strict_types=1);

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

class DatabaseTestCase extends TestCase
{
    private \PDO $db;

    final public function setUp(): void
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
            DROP TABLE IF EXISTS follows;
            DROP TABLE IF EXISTS authors;
                
            CREATE TABLE authors (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                username VARCHAR(100) NOT NULL UNIQUE,
                website VARCHAR(255) NULL,
                bio VARCHAR(255) NULL,
                PRIMARY KEY (id)
            );

            CREATE TABLE follows (
                followee_id INT(11) UNSIGNED NOT NULL,
                follower_id INT(11) UNSIGNED NOT NULL,
                FOREIGN KEY (followee_id) REFERENCES authors(id) ON DELETE CASCADE,
                FOREIGN KEY (follower_id) REFERENCES authors(id) ON DELETE CASCADE,
                PRIMARY KEY (followee_id, follower_id)
            );

            CREATE TABLE cheeps (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                author_id INT(11) UNSIGNED NOT NULL,
                message VARCHAR(240) NOT NULL,
                date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
                PRIMARY KEY (id)
            );
        SQL);
    }

    final protected function exec(string $sql): void
    {
        $this->db->exec($sql);
    }

    final public function tearDown(): void
    {
        if (!$this->db->inTransaction()) {
            return;
        }

        $this->db->rollBack();
    }
}

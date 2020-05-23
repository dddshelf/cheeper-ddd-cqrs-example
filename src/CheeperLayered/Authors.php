<?php

namespace CheeperLayered;

use mimic as m;

//snippet authors
class Authors
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = new \PDO(
            'mysql:host=127.0.0.1;dbname=db',
            'user',
            'pass',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    public function byId(int $id): ?Author
    {
        return $this->fetchOne(
            'SELECT id, username, website, bio FROM authors WHERE id = ?',
            $id
        );
    }

    public function byUsername(string $username): ?Author
    {
        return $this->fetchOne(
            'SELECT id, username, website, bio FROM authors WHERE username = ?',
            $username
        );
    }

    private function fetchOne(string $sql, string ...$params): ?Author
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            /** @var Author */
            $author = m\hydrate(Author::class, $result);
            return $author;
        }

        return null;
    }

    public function save(Author $author): void
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(<<<SQL
                INSERT
                    INTO authors (id, username, website, bio)
                    VALUES (:id, :username, :website, :bio)
                ON DUPLICATE KEY UPDATE
                    username = :username,
                    website = :website,
                    bio = :bio
            SQL);

            $stmt->execute([
                'id' => $author->id(),
                'username' => $author->username(),
                'website' => $author->website(),
                'bio' => $author->bio(),
            ]);

            if (!$author->id()) {
                $author->setId((int) $this->db->lastInsertId());
            }      

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new \RuntimeException($e->getMessage());
        }
    }
}
//end-snippet

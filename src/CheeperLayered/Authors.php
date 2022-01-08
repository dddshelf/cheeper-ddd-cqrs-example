<?php declare(strict_types=1);

namespace CheeperLayered;

use function mimic\hydrate;
//snippet authors
use PDO;

class Authors
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(
            'mysql:host=127.0.0.1;dbname=db',
            'user',
            'pass',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
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

    /**
     * @param mixed $params
     */
    private function fetchOne(string $sql, ...$params): ?Author
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @var Author */
            return hydrate(Author::class, $result);
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

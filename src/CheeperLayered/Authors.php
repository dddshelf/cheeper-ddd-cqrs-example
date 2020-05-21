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

    public function byUsername(string $username): ?Author
    {
        $stmt = $this->db->prepare(
            'SELECT id, username FROM authors WHERE username = ?'
        );
        $stmt->execute([$username]);

        if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            /** @var Author */
            $author = m\hydrate(Author::class, $result);
            return $author;
        }

        return null;
    }
}
//end-snippet

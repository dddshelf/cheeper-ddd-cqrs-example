<?php

namespace CheeperLayered;

//snippet cheeps
class Cheeps
{
    //ignore
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
    //end-ignore

    public function add(Cheep $cheep): void
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO cheeps (author_id, message) VALUES (?, ?)'
            );

            $stmt->execute([
                $cheep->authorId(),
                $cheep->message(),
            ]);

            $cheep->setId((int) $this->db->lastInsertId());

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new \RuntimeException($e->getMessage());
        }
    }
}
//end-snippet

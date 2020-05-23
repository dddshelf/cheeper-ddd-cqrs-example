<?php

namespace CheeperLayered;

class UnableToCreateCheepException extends \RuntimeException
{
}

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

            $this->db->commit();

            $cheep->setId((int) $this->db->lastInsertId());
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new UnableToCreateCheepException($e->getMessage());
        }
    }
}
//end-snippet

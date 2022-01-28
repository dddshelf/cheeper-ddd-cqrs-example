<?php
declare(strict_types=1);

namespace Cheeper\Chapter2\Layered;

use Cheeper\Chapter2\Cheep;
use function mimic\hydrate;
use PDO;

//snippet cheeps
class CheepDAO
{
    //ignore
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
    //end-ignore

    public function add(Cheep $cheep): void
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO cheeps (author_id, message, date) VALUES (?, ?, ?)'
            );

            $stmt->execute([
                $cheep->authorId(),
                $cheep->message(),
                $cheep->date()->format('Y-m-d H:i:s'),
            ]);

            $cheep->setId((int) $this->db->lastInsertId());

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new \RuntimeException($e->getMessage());
        }
    }

    /** @return Cheep[] */
    public function timelineOf(int $authorId): array
    {
        $sql = <<<SQL
                SELECT
                    username, cheeps.id, message, date
                FROM cheeps
                    JOIN authors ON cheeps.author_id = authors.id
                    LEFT JOIN follows ON follows.followee_id = authors.id
                WHERE author_id = :author_id OR follows.followee_id = authors.id
                ORDER BY date DESC
            SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['author_id' => $authorId]);

        $cheeps = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @var Cheep */
            $cheeps[] = hydrate(Cheep::class, [
                'username' => $row['username'],
                'id' => $row['id'],
                'message' => $row['message'],
                'date' => self::toDate((string) $row['date']),
            ]);
        }

        return $cheeps;
    }

    private static function toDate(string $date): \DateTimeImmutable
    {
        if (!$d = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date)) {
            throw new \RuntimeException(sprintf('Invalid datetime %s', $date));
        }

        return $d;
    }
}
//end-snippet

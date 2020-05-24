<?php

namespace CheeperHexagonal;

use PHPUnit\Framework\TestCase;

class PDOPostRepositoryTest extends TestCase
{
    private PDOPostRepository $postRepository;

    public function setUp(): void
    {
        $pdo = new \PDO('sqlite::memory:', '', '', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);

        $pdo->exec(
            <<<SQL
CREATE TABLE posts (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  content TEXT NOT NULL
);
SQL
        );

        $this->postRepository = new PDOPostRepository($pdo);
    }

    /**
     * @test
     */
    public function itShouldAddPost(): void
    {
        $p = Post::writeNewFrom('A title', 'Some content');

        $this->postRepository->add($p);

        $found = $this->postRepository->byId($p->id());
        $this->assertNotNull($found);
        $this->assertEquals($found->title(), 'A title');
        $this->assertEquals($found->content(), 'Some content');
    }
}

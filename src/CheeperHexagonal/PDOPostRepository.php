<?php declare(strict_types=1);

namespace CheeperHexagonal;

//snippet pdo-post-repository
use PDO;

class PDOPostRepository implements PostRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function byId(PostId $id): Post
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM posts WHERE id = ?'
        );

        $stmt->execute([$id->id()]);

        $fetch = (array) $stmt->fetch();

        /** @var Post */
        return \mimic\hydrate(Post::class, [
            'id' => new PostId((int) $fetch['id']),
            'title' => $fetch['title'],
            'content' => $fetch['content'],
        ]);
    }

    public function add(Post $post): Post
    {
        $stmt = $this->db->prepare(
            'INSERT INTO posts (title, content) VALUES (?, ?)'
        );

        $stmt->execute([
            $post->title(),
            $post->content(),
        ]);

        $post->setId(new PostId((int) $this->db->lastInsertId()));

        return $post;
    }
}
//end-snippet

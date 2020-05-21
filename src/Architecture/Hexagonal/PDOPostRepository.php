<?php

namespace Architecture\Hexagonal;

//snippet pdo-post-repository
class PDOPostRepository implements PostRepository
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
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

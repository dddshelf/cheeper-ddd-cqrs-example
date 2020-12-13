<?php

namespace CheeperHexagonal;

//snippet post-service
class PostService
{
    public function __construct(
        private PostRepository $postRepository
    ) { }

    public function createPost(string $title, string $content): Post
    {
        $post = Post::writeNewFrom($title, $content);

        $this->postRepository->add($post);

        return $post;
    }
}
//end-snippet

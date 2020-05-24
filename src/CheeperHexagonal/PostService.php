<?php

namespace CheeperHexagonal;

//snippet post-service
class PostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPost(string $title, string $content): Post
    {
        $post = Post::writeNewFrom($title, $content);

        $this->postRepository->add($post);

        return $post;
    }
}
//end-snippet

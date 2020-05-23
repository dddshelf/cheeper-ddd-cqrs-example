<?php

namespace Architecture\Hexagonal;

//snippet post-repository
interface PostRepository
{
    public function byId(PostId $id): Post;
    public function add(Post $post): Post;
}
//end-snippet

<?php

namespace Architecture\CQRS\Example;

use Architecture\CQRS\Domain\CategoryId;
use Architecture\CQRS\Domain\Post;
use Architecture\CQRS\Domain\PostId;

class TagId
{
}

//snippet bloated-post-repository
interface PostRepository
{
    public function save(Post $post): void;
    public function byId(PostId $id): Post;
    /** @return Post[] */
    public function all(): array;
    public function byCategory(CategoryId $categoryId): Post;
    /** @return Post[] */
    public function byTag(TagId $tagId): array;
    public function withComments(PostId $id): Post;
    /** @return Post[] */
    public function groupedByMonth(): array;
    // ...
}
//end-snippet

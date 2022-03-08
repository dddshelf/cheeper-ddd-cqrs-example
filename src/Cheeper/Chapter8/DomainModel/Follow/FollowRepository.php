<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\DomainModel\Follow\Follow;

interface FollowRepository
{
    public function numberOfFollowersFor(AuthorId $authorId): int;
    public function add(Follow $follow): void;
    public function fromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow;
    /** @return Follow[] */
    public function toAuthorId(AuthorId $authorId): array;
    /** @return Follow[] */
    public function findFollowingOf(AuthorId $authorId): array;
}

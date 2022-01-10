<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Infrastructure\Persistence;

use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\Chapter7\DomainModel\Follow\Follows;
use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\FollowId;
use function Functional\head;
use function Functional\reduce_left;
use function Functional\select;

final class InMemoryFollows implements Follows
{
    /** @var array<string, Follow> */
    public array $collection = [];

    public function ofId(FollowId $followId): ?Follow
    {
        $candidate = head(
            select($this->collection, fn (Follow $u): bool => $u->followId()->equals($followId))
        );

        if (null === $candidate) {
            return null;
        }

        return $candidate;
    }

    public function add(Follow $follow): void
    {
        $candidate = head(
            select($this->collection, fn (Follow $u): bool => $u->fromAuthorId()->equals($follow->fromAuthorId()) && $u->toAuthorId()->equals($follow->toAuthorId()))
        );

        if ((null !== $candidate && $candidate != $follow) || null === $candidate) {
            $this->collection[$follow->followId()->toString()] = $follow;
        }
    }

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return reduce_left(
            $this->collection,
            function(Follow $f, string $key, array $collection, int $initial) use($authorId): int {
                return $initial + ($f->fromAuthorId()->equals($authorId) ? 1 : 0);
            },
            0
        );
    }

    public function ofFromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow
    {
        $candidate = head(
            select($this->collection, fn (Follow $u): bool => $u->fromAuthorId()->equals($fromAuthorId) && $u->toAuthorId()->equals($toAuthorId))
        );

        return $candidate ?? null;
    }

    public function toAuthorId(AuthorId $authorId): array
    {
        return select($this->collection, fn (Follow $f): bool => $f->toAuthorId()->equals($authorId));
    }
}
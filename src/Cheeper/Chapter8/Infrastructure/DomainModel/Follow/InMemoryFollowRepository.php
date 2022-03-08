<?php

declare(strict_types=1);

namespace Cheeper\Chapter8\Infrastructure\DomainModel\Follow;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\AllChapters\DomainModel\Follow\FollowId;
use Cheeper\Chapter7\DomainModel\Follow\Follow;
use Cheeper\Chapter8\DomainModel\Follow\FollowRepository;
use function Functional\head;
use function Functional\reduce_left;
use function Functional\select;

final class InMemoryFollowRepository implements FollowRepository
{
    /** @var array<string, Follow> */
    public array $collection = [];

    public function ofId(FollowId $followId): ?Follow
    {
        $candidate = head(
            select($this->collection, fn (Follow $u): bool => $u->followId()->equals($followId))
        );

        return $candidate ?? null;
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
            fn (Follow $f, string $key, array $collection, int $initial): int => $initial + ($f->fromAuthorId()->equals($authorId) ? 1 : 0),
            0
        );
    }

    public function fromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow
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

    public function findFollowingOf(AuthorId $authorId): array
    {
        return select($this->collection, fn (Follow $f): bool => $f->fromAuthorId()->equals($authorId));
    }
}

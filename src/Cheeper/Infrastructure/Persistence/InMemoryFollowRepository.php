<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowRepository;
use Psl\Iter;
use Psl\Vec;

final class InMemoryFollowRepository implements FollowRepository
{
    /** @var list<Follow> */
    private array $follows = [];

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return count(
            Vec\filter(
                $this->follows,
                static fn (Follow $f) => $f->toAuthorId()->equals($authorId)
            )
        );
    }

    public function add(Follow $follow): void
    {
        $this->follows[] = $follow;
    }

    public function fromAuthorIdAndToAuthorId(AuthorId $fromAuthorId, AuthorId $toAuthorId): ?Follow
    {
        return Iter\first(
            Vec\filter(
                $this->follows,
                static fn (Follow $f) => $f->fromAuthorId()->equals($fromAuthorId) && $f->toAuthorId()->equals($toAuthorId)
            )
        );
    }

    public function toAuthorId(AuthorId $authorId): array
    {
        return Vec\filter(
            $this->follows,
            static fn (Follow $f) => $f->toAuthorId()->equals($authorId)
        );
    }
}

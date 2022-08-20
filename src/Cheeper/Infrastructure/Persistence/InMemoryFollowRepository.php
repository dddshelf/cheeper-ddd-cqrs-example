<?php

declare(strict_types=1);

namespace Cheeper\Infrastructure\Persistence;

use Cheeper\DomainModel\Author\AuthorId;
use Cheeper\DomainModel\Follow\Follow;
use Cheeper\DomainModel\Follow\FollowRepository;

final class InMemoryFollowRepository implements FollowRepository
{
    /** @var Follow[] */
    private array $follows = [];

    public function numberOfFollowersFor(AuthorId $authorId): int
    {
        return count(
            array_filter(
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
        $candidates = array_filter(
            $this->follows,
            static fn (Follow $f) => $f->fromAuthorId()->equals($fromAuthorId) && $f->toAuthorId()->equals($toAuthorId)
        );

        return current($candidates);
    }

    public function toAuthorId(AuthorId $authorId): array
    {
        return array_filter(
            $this->follows,
            static fn (Follow $f) => $f->toAuthorId()->equals($authorId)
        );
    }
}

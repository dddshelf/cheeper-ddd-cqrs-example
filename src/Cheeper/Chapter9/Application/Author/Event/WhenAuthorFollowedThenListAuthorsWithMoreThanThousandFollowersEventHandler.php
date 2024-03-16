<?php

declare(strict_types=1);


namespace Cheeper\Chapter9\Application\Author\Event;

use Cheeper\AllChapters\DomainModel\Author\AuthorId;
use Cheeper\Chapter7\Application\Author\Projection\IncrementCountFollowersProjection;
use Cheeper\Chapter7\Application\ProjectionBus;
use Cheeper\Chapter8\DomainModel\Follow\AuthorFollowed;
use Cheeper\Chapter8\DomainModel\Follow\FollowRepository;

final readonly class WhenAuthorFollowedThenListAuthorsWithMoreThanThousandFollowersEventHandler
{
    public function __construct(
        private ProjectionBus $projectionBus,
        private FollowRepository $followRepository
    ) {
    }

    public function __invoke(AuthorFollowed $event): void
    {
        if ($this->followRepository->numberOfFollowersFor(AuthorId::fromString($event->toAuthorId())) >= 1000) {
            $this->projectionBus->project(
                IncrementCountFollowersProjection::ofAuthor(
                    $event->toAuthorId()
                )
            );
        }
    }
}
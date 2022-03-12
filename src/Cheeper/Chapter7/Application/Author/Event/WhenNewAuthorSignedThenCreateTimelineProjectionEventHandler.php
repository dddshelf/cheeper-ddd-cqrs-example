<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjection;
use Cheeper\Chapter7\Application\Author\Projection\CreateFollowersCounterProjectionHandlerInterface;
use Cheeper\Chapter7\Application\Author\Projection\CreateTimelineProjection;
use Cheeper\Chapter7\Application\Author\Projection\CreateTimelineProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;

final class WhenNewAuthorSignedThenCreateTimelineProjectionEventHandler
{
    public function __construct(
        private CreateTimelineProjectionHandlerInterface $projector
    ) {
    }

    public function __invoke(NewAuthorSigned $event): void
    {
        $this->projector->__invoke(
            CreateTimelineProjection::ofAuthor(
                $event->authorId(),
                $event->authorUsername()
            )
        );
    }
}
<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\AuthorFollowed;

use Cheeper\Chapter7\Application\Author\Projection\IncrementCountFollowersProjection;
use Cheeper\Chapter7\Application\ProjectionBus;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet snippet
final class WhenAuthorFollowedThenIncrementFollowersProjectionEventHandler
{
    public function __construct(
        private ProjectionBus $projectionBus
    ) {
    }

    public function __invoke(AuthorFollowed $event): void
    {
        $this->projectionBus->project(
            IncrementCountFollowersProjection::ofAuthor(
                $event->toAuthorId()
            )
        );
    }
}
//end-snippet

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\AuthorFollowed;

use Cheeper\Chapter7\Application\Author\Projection\IncrementCountFollowersProjection;
use Cheeper\Chapter7\Application\ProjectionBus;

// We use AuthorFollowed from chapter 8 so this handler does not
// handle the correct Domain Event, so the demo works as expected
use Cheeper\Chapter8\DomainModel\Follow\AuthorFollowed;

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

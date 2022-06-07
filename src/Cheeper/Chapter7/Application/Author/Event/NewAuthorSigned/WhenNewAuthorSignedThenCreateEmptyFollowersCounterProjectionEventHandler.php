<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\NewAuthorSigned;

use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandlerInterface;

// We use NewAuthorSigned from chapter 8 so this handler does not
// handle the correct Domain Event, so the demo works as expected
use Cheeper\Chapter8\DomainModel\Author\NewAuthorSigned;

//snippet author-followed-event-handler
final class WhenNewAuthorSignedThenCreateEmptyFollowersCounterProjectionEventHandler
{
    public function __construct(
        private CountFollowersProjectionHandlerInterface $projectionHandler
    ) {
    }

    public function __invoke(NewAuthorSigned $event): void
    {
        $this->projectionHandler->__invoke(
            CountFollowersProjection::ofAuthor($event->authorId())
        );
    }
}
//end-snippet

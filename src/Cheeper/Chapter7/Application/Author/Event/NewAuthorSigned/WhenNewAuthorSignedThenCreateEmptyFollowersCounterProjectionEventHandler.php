<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event\NewAuthorSigned;

use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Author\NewAuthorSigned;

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

<?php

declare(strict_types=1);

namespace Cheeper\Chapter7\Application\Author\Event;

use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjection;
use Cheeper\Chapter7\Application\Author\Projection\CountFollowersProjectionHandlerInterface;
use Cheeper\Chapter7\DomainModel\Follow\AuthorFollowed;

//snippet author-followed-event-handler
final class AuthorFollowedEventHandler
{
    public function __construct(
        private CountFollowersProjectionHandlerInterface $projectionHandler
    ) {
    }

    public function __invoke(AuthorFollowed $event): void
    {
        $this->projectionHandler->__invoke(
            CountFollowersProjection::ofAuthor($event->toAuthorId())
        );
    }
}
//end-snippet
